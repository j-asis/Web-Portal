<?php

class UploadImg
{
    const MAX_FILE_SIZE = 2000000;
    const MAX_POST_SIZE = 8000000;
    const NO_FILE_UPLOAD_ERROR = 4;
    const NO_ERROR = 0;

    public function __construct($file)
    {
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
            $this->error++;
            $this->error_message[] = "File already exists!";
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
        $this->error = 0;
        $type_check = 0;
        $file_types = array('jpg','jpeg','png','gif');
        foreach ($file_types as $type) {
            if ($this->file_type === $type) {
                $type_check++;
            }
        }
        
        if (getimagesize($this->file['tmp_name']) === false) {
            $this->error++;
            $this->error_message[] = "File is not an image!";
        }
        if ($this->file['size'] > self::MAX_FILE_SIZE) {
            $this->error++;
            $this->error_message[] = "File size too big, can only upload 2000 bytes";
        }
        if (!($type_check > 0)) {
            $this->error++;
            $this->error_message[] = "Wrong File type, can only upload jpeg, jpg, png, or gif";
        }
        if ($this->error === 0) {
            return true;
        } else {
            return false;
        }
        return false;
    }

}
