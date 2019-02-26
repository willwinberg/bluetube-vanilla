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