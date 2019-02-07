<?php

class formInputValidator {

   private $dbConnection;
   private $errorArray = array();

   public function __construct($dbConnection) {
      $this->dbConnection = $dbConnection;
   }

   public function validateNewUserData($sanitizedData) {
      $this->validateFirstName($sanitizedData["firstName"]);
      $this->validateLastName($sanitizedData["lastName"]);
      $this->validateUsername($sanitizedData["username"]);
      $this->validateEmails($sanitizedData["email"], $sanitizedData["emailConfirm"]);
      $this->validatePasswords($sanitizedData["password"], $sanitizedData["passwordConfirm"]);
   }

   private function validateFirstName($firstName) {
      if (strlen($firstName) > 30 || strlen($firstName) < 2) {
         array_push($this->errorArray, ErrorMessage::$firstNameLength);
      }
   }

   private function validateLastName($lastName) {
      if (strlen($lastName) > 30 || strlen($lastName) < 2) {
         array_push($this->errorArray, ErrorMessage::$lastNameLength);
      }
   }

   private function validateUsername($username) {
      if (strlen($username) > 20 || strlen($username) < 5) {
         array_push($this->errorArray, ErrorMessage::$usernameLength);
         return;
      }

      $query = $this->dbConnection->prepare(
         "SELECT username FROM users WHERE username=:username"
      );
      $query->bindParam(":username", $username);
      $query->execute();

      if ($query->rowCount() != 0) {
         array_push($this->errorArray, ErrorMessage::$usernameTaken);
      }
   }

   private function validateEmails($email, $emailConfirm) {
      if ($email != $emailConfirm) {
         array_push($this->errorArray, ErrorMessage::$emailsDoNotMatch);
         return;
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         array_push($this->errorArray, ErrorMessage::$emailInvalid);
         return;
      }

      $query = $this->dbConnection->prepare(
         "SELECT email FROM users WHERE email=:email"
      );
      $query->bindParam(":email", $email);
      $query->execute();

      if ($query->rowCount() != 0) {
         array_push($this->errorArray, ErrorMessage::$emailTaken);
      }
   }

   private function validatePasswords($password, $passwordConfirm) {
      if ($password != $passwordConfirm) {
         array_push($this->errorArray, ErrorMessage::$passwordsDoNotMatch);
         return;
      }

      if (preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $password)) {
         array_push($this->errorArray, ErrorMessage::$passwordInsecure);
         return;
      }

      if (strlen($password) > 30 || strlen($password) < 8) {
         array_push($this->errorArray, ErrorMessage::$passwordLength);
      }
   }
   
   public function getError($errorMessage) {
      if (in_array($errorMessage, $this->errorArray)) {
         return "<span class='errorMessage'>$errorMessage</span>";
      }
   }
}

?>