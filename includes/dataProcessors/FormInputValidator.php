<?php

class formInputValidator {

   private $db;
   public $errors = array();

   public function __construct($db) {
      $this->db = $db;
   }

   public function validateUserData($sanitizedData) {
      $this->validateFirstName($sanitizedData["firstName"]);
      $this->validateLastName($sanitizedData["lastName"]);
      $this->validateUsername($sanitizedData["username"]);
      $this->validateEmails($sanitizedData["email"], $sanitizedData["emailConfirm"]);
      $this->validatePasswords($sanitizedData["password"], $sanitizedData["passwordConfirm"]);
      if (isset($sanitizedData["oldPassword"])) {
         $this->validateOldPassword($sanitizedData["oldPassword"]);
      }
   }

   private function validateFirstName($firstName) {
      if (strlen($firstName) > 30 || strlen($firstName) < 2) {
         array_push($this->errors, Error::$firstNameLength);
      }
   }

   private function validateLastName($lastName) {
      if (strlen($lastName) > 30 || strlen($lastName) < 2) {
         array_push($this->errors, Error::$lastNameLength);
      }
   }

   private function validateUsername($username) {
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

   private function validateEmails($email, $emailConfirm) {
      if ($email != $emailConfirm) {
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

      if ($query->rowCount() !== 0) {
         array_push($this->errors, Error::$emailTaken);
      }
   }

   private function validatePasswords($password, $passwordConfirm) {
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

   private function validateOldPassword($oldPassword, $username) {
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
   
   public function getError($errorMessage) {
      if (in_array($errorMessage, $this->errors)) {
         return "<span class='errorMessage'>$errorMessage</span>";
      }
   }
}

?>