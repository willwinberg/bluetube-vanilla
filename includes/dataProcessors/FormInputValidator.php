<?php

class formInputValidator {

   private $db;
   public $errors = array();

   public function __construct($db) {
      $this->db = $db;
   }

   public function changesMade($data) {
      $differenceArr = array_diff_assoc($data, $_POST);

      if (empty($differenceArr)) {
        $this->errors[] = Error::$noChanges; 
      }
   }

   public function validateFirstName($firstName) {
      if (strlen($firstName) > 30 || strlen($firstName) < 2) {
         $this->errors[] = Error::$firstNameLength;
      }
   }

   public function validateLastName($lastName) {
      if (strlen($lastName) > 30 || strlen($lastName) < 2) {
         $this->errors[] = Error::$lastNameLength;
      }
   }

   public function validateUsername($username) {
      if (!ctype_alnum($username)) {
         $this->errors[] = Error::$usernameChars;
      }
      if (strlen($username) > 20 || strlen($username) < 5) {
         $this->errors[] = Error::$usernameLength;
         return;
      }

      $query = $this->db->prepare(
         "SELECT username FROM users WHERE username=:username"
      );
      $query->bindParam(":username", $username);
      $query->execute();

      if ($query->rowCount() !== 0) {
         $this->errors[] = Error::$usernameTaken;
      }
   }
   
   // 
      public function validateImage() {
      $targetDir = "assets/images/profilePictures/";
      $targetFile = $targetDir . uniqid() . basename($_FILES["image"]["name"]);
      $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
      
      if (isset($_POST["imageUpdate"])) {
         $check = getimagesize($_FILES["image"]["tmp_name"]);
         if (!$check) {
            $this->errors[] = Error::$notImage;
            return;
         }
      }
      if ($_FILES["image"]["size"] > 5000000) { // 5mb
         $this->errors[] = Error::$imageSize;
         return;
      }
      $allowedTypes = array("jpg", "png", "jpeg", "gif");
      if (!in_array($imageFileType, $allowedTypes)) {
         $this->errors[] = Error::$imageType;
         return;
      }
  
      if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
         $this->errors[] = Error::$imageUnknown;
         return;
      }
      $this->resize_crop_image(300, 300, $targetFile, $targetFile);
      return $targetFile;
   }

   //resize and crop image by center and make square
   // credit: https://polyetilen.lt/en/author/polyetilen
   private function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
      $imgsize = getimagesize($source_file);
      $width = $imgsize[0];
      $height = $imgsize[1];
      $mime = $imgsize['mime'];

      switch($mime){
         case 'image/gif':
               $image_create = "imagecreatefromgif";
               $image = "imagegif";
               break;

         case 'image/png':
               $image_create = "imagecreatefrompng";
               $image = "imagepng";
               $quality = 7;
               break;

         case 'image/jpeg':
               $image_create = "imagecreatefromjpeg";
               $image = "imagejpeg";
               $quality = 80;
               break;

         default:
               return false;
               break;
      }
      
      $dst_img = imagecreatetruecolor($max_width, $max_height);
      $src_img = $image_create($source_file);
      
      $width_new = $height * $max_width / $max_height;
      $height_new = $width * $max_height / $max_width;
      //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
      if($width_new > $width){
         //cut point by height
         $h_point = (($height - $height_new) / 2);
         //copy image
         imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
      }else{
         //cut point by width
         $w_point = (($width - $width_new) / 2);
         imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
      }
      
      $image($dst_img, $dst_dir, $quality);

      if($dst_img)imagedestroy($dst_img);
      if($src_img)imagedestroy($src_img);
   }

   public function validateEmails($email, $emailConfirm, $currentEmail = false) {
      if ($email !== $emailConfirm) {
         $this->errors[] = Error::$emailsDoNotMatch;
         return;
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $this->errors[] = Error::$emailInvalid;
         return;
      }

      $query = $this->db->prepare(
         "SELECT email FROM users WHERE email=:email"
      );
      $query->bindParam(":email", $email);
      $query->execute();

      if ($query->rowCount() !== 0 && $email !== $currentEmail) {
         $this->errors[] = Error::$emailTaken;
      }
   }

   public function validatePasswords($password, $passwordConfirm) {
      if ($password != $passwordConfirm) {
         $this->errors[] = Error::$passwordsDoNotMatch;
         return;
      }

      if (preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $password)) {
         $this->errors[] = Error::$passwordNotSecure;
         return;
      }

      if (strlen($password) > 30 || strlen($password) < 8) {
         $this->errors[] = Error::$passwordLength;
      }
   }

   public function validateOldPassword($oldPassword, $username) {
      $password = hash("sha256", $oldPassword);
   
      $query = $this->db->prepare(
         "SELECT * FROM users WHERE username=:username AND password=:password"
      );
      $query->bindParam(":username", $username);
      $query->bindParam(":password", $password);
      $query->execute();

      if($query->rowCount() == 0) {
         $this->errors[] = Error::$passwordIncorrect;
      }
    }
   
   public function error($errorMessage) {
      if (in_array($errorMessage, $this->errors)) {
         return "<span class='errorMessage'>$errorMessage</span>";
      }
   }
}

?>