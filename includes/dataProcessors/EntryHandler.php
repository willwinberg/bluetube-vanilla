<?php

class EntryHandler {

   static public function login($db, $data) {
      $username = $data["username"];
      $password = hash("sha256", $data["password"]);
      $query = $db->prepare(
         "SELECT * FROM users WHERE username=:username AND password=:password"
      );
      $query->bindParam(":username", $username);
      $query->bindParam(":password", $password);
      $query->execute();
      if ($query->rowCount() === 1) {
         return Success::$login;
      } else {
         return ErrorMsg::$login;
      }
   }

   static public function registerNewUser($db, $data) {
      $firstName = $data["firstName"];
      $lastName = $data["lastName"];
      $username = $data["username"];
      $email = $data["email"];
      $password = hash("sha256", $data["password"]);
      $image = "assets/images/profilePictures/default.png";

      $query = $db->prepare(
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
         return ErrorMsg::$register;
      }
   }
}