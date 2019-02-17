<?php
require_once("includes/dataProcessors/Success.php"); 

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
      $query->execute();

      if ($query->rowCount() === 1) {
         $this->success = Success::$login;
         return true;
      } else {
         $this->error = Error::$loginFailed;
         return false;
      }
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
      $query->execute();

      if ($query->rowCount() === 1) {
         $this->success = Success::$register;
      } else {
         $this->error = Error::$registerFailed;
      }
      return;
   }

   public function updateDetails($data, $username) {
      $query = $this->db->prepare(
         "UPDATE users SET firstName=:fistName, lastName=:lastName, email=:email WHERE username=:username");
      $query->bindParam(":fistName", $data["firstName"]);
      $query->bindParam(":lastName", $data["lastName"]);
      $query->bindParam(":email", $data["email"]);
      $query->bindParam(":username", $username);
      $query->execute();

      if ($query->rowCount() === 1) {
         $this->success = Success::$detailsUpdate;
      } else {
         $this->error = Error::$detailsUpdateFailed;
      }
      return;
   }

   public function updatePassword($password, $username) {
      $password = hash("sha256", $password);

      $query = $this->db->prepare(
         "UPDATE users SET password=:password WHERE username=:username");
      $query->bindParam(":password", $password);
      $query->bindParam(":username", $username);
      $success = $query->execute();

      if ($query->rowCount() === 1) {
         $this->success = Success::$passwordUpdate;
      } else {
         $this->error = Error::$passwordUpdateFailed;
      }
      return;
   }

   public function error() {
      if ($this->error) {
         return "
            <div class='alert alert-danger'>
               $this->error
            </div>   
         ";
      }
   }

   public function success() {
      if ($this->success) {
         return "
            <div class='alert alert-success'>
               $this->success
            </div>
         ";
      }
   }


}
?>