<?php

class UserAccountHandler {

   private $dbConnection;

   public function __construct($dbConnection) {
      $this->dbConnection = $dbConnection;
   }

   public function login($username, $password) {
      $password = hash("sha512", $password);
      $query = $this->dbConnection->prepare(
         "SELECT * FROM users WHERE username=:username AND password=:password"
      );

      $query->bindParam(":username", $username);
      $query->bindParam(":password", $password);
      $query->execute();

      if ($query->rowCount() == 1) {
         return true;
      }
      else {
         array_push($this->errorArray, ErrorMessage::$loginFailed);
         return false;
      }
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
}
?>