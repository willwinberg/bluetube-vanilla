<?php

class UserAccountHandler {

   private $dbConnection;
   public $error;

   public function __construct($dbConnection) {
      $this->dbConnection = $dbConnection;
   }

   public function login($loginData) {
      $username = $loginData["username"];
      $password = hash("sha512", $loginData["password"]);

      $query = $this->dbConnection->prepare(
         "SELECT * FROM users WHERE username=:username AND password=:password"
      );
      $query->bindParam(":username", $username);
      $query->bindParam(":password", $password);
      $query->execute();

      if ($query->rowCount() !== 1) {
         $this->error = ErrorMessage::$loginFailed;
      }
      return;
   }

   public function registerNewUser($userData) {
      $firstName = $userData["firstName"];
      $lastName = $userData["lastName"];
      $username = $userData["username"];
      $email = $userData["email"];
      $password = hash("sha512", $userData["password"]);
      $image = "assets/images/profilePictures/default.png";

      $query = $this->dbConnection->prepare(
         "INSERT INTO users (firstName, lastName, username, email, password, image)
         VALUES (:firstName, :lastName, :username, :email, :password, :image)"
      );

      $query->bindParam(":firstName", $firstName);
      $query->bindParam(":lastName", $lastName);
      $query->bindParam(":username", $username);
      $query->bindParam(":email", $email);
      $query->bindParam(":password", $password);
      $query->bindParam(":image", $image);
      
      return $query->execute();
   }

   public function getError($errorMessage) {
      if ($this->error) {
         return "<span class='errorMessage'>$errorMessage</span>";
      }
   }
}
?>