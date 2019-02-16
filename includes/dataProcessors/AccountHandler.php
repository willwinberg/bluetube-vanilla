<?php

class AccountHandler {

   private $db;
   public $error;

   public function __construct($db) {
      $this->db = $db;
   }

   public function login($loginData) {
      $username = $loginData["username"];
      $password = hash("sha256", $loginData["password"]);
      $query = $this->db->prepare(
         "SELECT * FROM users WHERE username=:username AND password=:password"
      );
      $query->bindParam(":username", $username);
      $query->bindParam(":password", $password);
      $query->execute();

      if ($query->rowCount() !== 1) {
         $this->error = Error::$loginFailed;
      }
      return;
   }

   public function registerNewUser($userData) {
      $firstName = $userData["firstName"];
      $lastName = $userData["lastName"];
      $username = $userData["username"];
      $email = $userData["email"];
      $password = hash("sha256", $userData["password"]);
      $image = "assets/images/profilePictures/default.png";

      $query = $this->db->prepare(
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

   public function updateDetails($data, $username) {
      $query = $this->db->prepare(
         "UPDATE users SET firstName=:fistName, lastName=:lastName, username=:newUsername email=:email WHERE username=:username");
      $query->bindParam(":fistName", $data["firstName"]);
      $query->bindParam(":lastName", $data["lastName"]);
      $query->bindParam(":email", $data["email"]);
      $query->bindParam(":username", $username);
      $query->bindParam(":newUsername", $data["username"]);

      return $query->execute();
   }

   public function updatePassword($data, $username) {
      $query = $this->db->prepare(
         "UPDATE users SET password=:password WHERE username=:username");
      $password = hash("sha256", $data["password"]);
      $query->bindParam(":password", $password);
      $query->bindParam(":username", $username);

      return $query->execute();
   }

   public function getError($errorMessage) {
      if ($this->error) {
         return "<span class='errorMessage'>$errorMessage</span>";
      }
   }
}
?>