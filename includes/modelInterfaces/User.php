<?php

class User {

   private $db;
   public $firstName, $lastName, $username, $email, $image;

   public function __construct($db, $username) {
      $this->db = $db;

      $query = $this->db->prepare(
         "SELECT * FROM users WHERE username = :username"
      );
      $query->bindParam(":username", $username);
      $query->execute();

      $user = $query->fetch(PDO::FETCH_ASSOC);

      $this->firstName = $user["firstName"];
      $this->lastName = $user["lastName"];
      $this->username = $user["username"];
      $this->email = $user["email"];
      $this->image = $user["image"];
      $this->signUpDate = $user["signUpDate"];
   }
   
   public static function loggedIn() {
      return isset($_SESSION["loggedIn"]);
   }

   public function getFullName() {
      return $this->firstName . " " . $this->lastName;
   }

   public static function isLoggedIn() {
      return isset($_SESSION["loggedIn"]);
   }

   public function subscribe($toUsername) {
      $query = $this->db->prepare(
         "INSERT INTO subscribes (toUsername, fromUsername) VALUES (:toUsername, :fromUsername)"
      );
      $query->bindParam(":toUsername", $toUsername);
      $query->bindParam(":fromUsername", $this->username);
      $query->execute();
   }

   public function unSubscribe($toUsername) {
      $query = $this->db->prepare(
         "DELETE FROM subscribes WHERE (toUsername=:toUsername AND fromUsername=:fromUsername)"
      );
      $query->bindParam(":toUsername", $toUsername);
      $query->bindParam(":fromUsername", $this->username);
      $query->execute();
   }

   public function subscriberCount() {
      $query = $this->db->prepare(
         "SELECT * FROM subscribes WHERE toUsername=:toUsername"
      );
      $query->bindParam(":toUsername", $this->username);
      $query->execute();
      return $query->rowCount();
   }

   // Array of usernames to which $this is subscribed
   public function subscriptionsArray() {
      $query = $this->db->prepare(
         "SELECT toUsername FROM subscribes WHERE fromUsername=:fromUsername"
      );
      $query->bindParam(":fromUsername", $this->username);
      $query->execute();
      
      $subscribers = array();

      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $user = new User($this->db, $row["toUsername"]);
         array_push($subscribers, $user->username);
      }

      return $subscribers;
   }

}
?>