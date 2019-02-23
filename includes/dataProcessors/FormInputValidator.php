<?php

class formInputValidator {

   private $db;
   public $errors = array();

   public function __construct($db) {
      $this->db = $db;
   }

   public function validateFirstName($firstName) {
      if (strlen($firstName) > 30 || strlen($firstName) < 2) {
         array_push($this->errors, Error::$firstNameLength);
      }
   }

   public function validateLastName($lastName) {
      if (strlen($lastName) > 30 || strlen($lastName) < 2) {
         array_push($this->errors, Error::$lastNameLength);
      }
   }

   public function validateUsername($username) {
      if (strlen($username) > 20 || strlen($username) < 5) {
         array_push($this->errors, Error::$usernameLength);
         return;
      }

      $query = $this->db->prepare(
         "SELECT username FROM users WHERE username=:username"
      );
      $query->bindParam(":username", $username);
      $query->execute();

      if ($query->rowCount() !== 0) {
         array_push($this->errors, Error::$usernameTaken);
      }
   }
   
   public function validateImage() {
      $targetDir = "assets/images/profilePictures/";
      $targetFile = $targetDir . uniqid() . basename($_FILES["image"]["name"]);
      $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
      
      if (isset($_POST["imageUpdate"])) {
         $check = getimagesize($_FILES["image"]["tmp_name"]);

         if (!$check) {
            $this->errors[] = Error::$notImage;
         }
      }

      if ($_FILES["image"]["size"] > 5000000) { // 5mb
         $this->errors[] = Error::$imageSize;
      }

      $allowedTypes = array("jpg", "png", "jpeg", "gif");

      if (!in_array($imageFileType, $allowedTypes)) {
         $this->errors[] = Error::$imageType;
      }
  
      if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
         $this->errors[] = Error::$imageUnknown;
      }

      return $targetFile;
   }

   public function validateEmails($email, $emailConfirm, $currentEmail = false) {
      if ($email !== $emailConfirm) {
         array_push($this->errors, Error::$emailsDoNotMatch);
         return;
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         array_push($this->errors, Error::$emailInvalid);
         return;
      }

      $query = $this->db->prepare(
         "SELECT email FROM users WHERE email=:email"
      );
      $query->bindParam(":email", $email);
      $query->execute();

      if ($query->rowCount() !== 0 && $email !== $currentEmail) {
         array_push($this->errors, Error::$emailTaken);
      }
   }

   public function validatePasswords($password, $passwordConfirm) {
      if ($password != $passwordConfirm) {
         array_push($this->errors, Error::$passwordsDoNotMatch);
         return;
      }

      if (preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $password)) {
         array_push($this->errors, Error::$passwordNotSecure);
         return;
      }

      if (strlen($password) > 30 || strlen($password) < 8) {
         array_push($this->errors, Error::$passwordLength);
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
         array_push($this->errors, Error::$passwordIncorrect);
      }
    }
   
   public function error($errorMessage) {
      if (in_array($errorMessage, $this->errors)) {
         return "<span class='errorMessage'>$errorMessage</span>";
      }
   }
}

?>