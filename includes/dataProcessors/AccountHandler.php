<?php

class AccountHandler {

   private $db;

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
      $this->success = $query->execute();

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
      
      $this->success = $query->execute();
   }

   public function updateDetails($data, $username) {
      $query = $this->db->prepare(
         "UPDATE users SET firstName=:fistName, lastName=:lastName, email=:email WHERE username=:username");
      $query->bindParam(":fistName", $data["firstName"]);
      $query->bindParam(":lastName", $data["lastName"]);
      $query->bindParam(":email", $data["email"]);
      $query->bindParam(":username", $username);

      $this->success = $query->execute();
   }

   public function updatePassword($password, $username) {
      $password = hash("sha256", $password);

      $query = $this->db->prepare(
         "UPDATE users SET password=:password WHERE username=:username");
      $query->bindParam(":password", $password);
      $query->bindParam(":username", $username);

      
      $query->execute();
   }

   public function error($message) {
      if ($this->error) {
         return "<span class='errorMessage'>$message</span>";
      }
   }

   public function success($message) {
      if ($this->success) {
         return "<span class='successMessage'>$message</span>";
      }
   }
}
?>