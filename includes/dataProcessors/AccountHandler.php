<?php

class AccountHandler {

   private $db;
   public $validated;

   public function __construct($db) {
      $this->db = $db;
      $this->validated = false;
      $this->message = "";
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
         $this->validated = true;
         return Success::$login;
      } else {
         return Error::$login;
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
         return Success::$register;
      } else {
         return Error::$registerFailed;
      }
   }

   public function updateDetails($data, $username) {
      $query = $this->db->prepare(
         "UPDATE users SET
         firstName=:firstName,
         lastName=:lastName,
         email=:email WHERE
         username=:username"
      );
      $query->bindParam(":firstName", $data["firstName"]);
      $query->bindParam(":lastName", $data["lastName"]);
      $query->bindParam(":email", $data["email"]);
      $query->bindParam(":username", $username);
      $query->execute();

      if ($query->rowCount() === 1) {
         $this->message = Success::$detailsUpdate;
      } else {
         $this->message = Error::$detailsUpdate;
      }
      return;
   }

   public function updateImage($path, $username) {
      $query = $this->db->prepare(
         "UPDATE users SET image=:image
         WHERE username=:username"
      );
      $query->bindParam(":image", $path);
      $query->bindParam(":username", $username);
      $query->execute();

      if ($query->rowCount() === 1) {
         $this->message = Success::$image;
      } else {
         $this->message = Error::$image;
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
         $this->message = Success::$passwordUpdate;
      } else {
         $this->message = Error::$passwordUpdate;
      }
      return;
   }
   
   public function updateVideo($update) {
      $query = $this->db->prepare(
         "UPDATE videos SET title=:title, description=:description,
         privacy=:privacy, category=:category WHERE id=:videoId"
      );       
      $query->bindParam(":title", $update["title"]);
      $query->bindParam(":description", $update["description"]);
      $query->bindParam(":privacy", $update["privacy"]);
      $query->bindParam(":category", $update["category"]);
      $query->bindParam(":videoId", $update["videoId"]);
      $success = $query->execute();
      $count = $query->rowCount();

      if ($count === 1) {
         return Success::$videoUpdate;
      } else {
         return Error::$videoUpdate;
      }
   }

   public function error($message) {
      if ($this->message === $message) {
         return "
            <div class='alert alert-danger'>
               $this->error
            </div>   
         ";
      }
   }

   public function success($message) {
      if ($this->message === $message) {
         return "
            <div class='alert alert-success'>
               $this->message
            </div>
         ";
      }
   }

}
?>