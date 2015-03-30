<?php

class UploadImg
{
    const MAX_FILE_SIZE = 2000000;

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






?>





<?php
/*
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
*/
?> 