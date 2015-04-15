<?php

class UploadImg
{
    const MAX_FILE_SIZE = 2000000;
    const MAX_POST_SIZE = 8000000;
    const NO_FILE_UPLOAD_ERROR = 4;
    const NO_ERROR = 0;
    const SIZE_ERROR = 1;


    public function __construct()
    {
        $this->error = array();
        $this->error['fatal'] = false;
        $this->error['no_file'] = false;
        $this->error['upload'] = false;
        $this->error['file_exists'] = false;
        $this->error['file'] = false;
        $this->error['size'] = false;
        $this->error['type'] = false;
        if (self::MAX_POST_SIZE < $_SERVER['CONTENT_LENGTH']) {
            $this->error['fatal'] = true;
        }
    }

    public function set($file)
    {
        if ($file['error'] === self::NO_FILE_UPLOAD_ERROR) {
            $this->error['no_file'] = true;
        }
        if ($file['error'] > self::NO_ERROR) {
            $this->error['upload'] = true;
        }
        $this->file_type = pathinfo($file['name'],PATHINFO_EXTENSION);
        $this->file = $file;
        $this->filename = $file['name'];
    }

    public function rename($filename)
    {
        $this->filename = $filename . "." . $this->file_type;
    }

    public function save($destination)
    {
        if (file_exists($destination . $this->filename)) {
            $this->error['file_exists'] = true;
        } else {
            if (move_uploaded_file($this->file["tmp_name"], APP_DIR . $destination . $this->filename)) {
                return str_replace('webroot','',$destination . $this->filename);
            } else {
                return false;
            }
        }
    }

    public function isFileAccepted()
    {
        $type_check = 0;
        $file_types = array('jpg','jpeg','png','gif');
        foreach ($file_types as $type) {
            if ($this->file_type === $type) {
                $type_check++;
            }
        }
        
        if (getimagesize($this->file['tmp_name']) === false) {
            $this->error['file'] = true;
        }
        if ($this->file['size'] > self::MAX_FILE_SIZE) {
            $this->error['size'] = true;
        }
        if (!($type_check > 0)) {
            $this->error['type'] = true;
        }
        if (!$this->hasError()) {
            return true;
        } else {
            return false;
        }
        return false;
    }

    public function hasError()
    {
        $count = 0;
        foreach ($this->error as $error) {
            if ($error) {
                $count++;
            }
        } 
        return ($count > 0);
    }

}
