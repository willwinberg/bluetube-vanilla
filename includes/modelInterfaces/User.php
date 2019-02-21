<?php

class User {

   private $db;
   public $user; // CHANGE

   public function __construct($db, $username) {
      $query = $db->prepare(
         "SELECT * FROM users WHERE username = :username"
      );
      $query->bindParam(":username", $username);
      $query->execute();

      $user = $query->fetch(PDO::FETCH_ASSOC);

      $this->db = $db;
      $this->user = $user;

      $this->firstName = $user["firstName"];
      $this->lastName = $user["lastName"];
      $this->username = $user["username"];
      $this->email = $user["email"];
      // $this->bannerImg = $user["coverPhoto"];
      $this->bannerImg = "assets/images/banners/default-banner.png";
      $this->image = $user["image"];
   }
   
   public static function isLoggedIn() {
      return isset($_SESSION["loggedIn"]);
   }
   
   public static function isNotLoggedIn() {
      return !isset($_SESSION["loggedIn"]);
   }

   public function username() {
      return $this->user["username"];
   }

   public function firstName() {
      return $this->user["firstName"];
   }

   public function lastName() {
      return $this->user["lastName"];
   }

   public function fullName() {
      return $this->firstName() . " " . $this->lastName();
   }

   public function email() {
      return $this->user["email"];
   }

   public function image() {
      return $this->user["image"];
   }

   public function bannerImg() {
      return $this->user["bannerImg"];
   }

   public function signUpDate() {
        $date = $this->user["signUpDate"];

        return date("F jS, Y", strtotime($date));
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

   public function getSubscriberCount() {
      $query = $this->db->prepare(
         "SELECT * FROM subscribes WHERE toUsername=:toUsername"
      );
      $query->bindParam(":toUsername", $this->username);
      $query->execute();
      return $query->rowCount();
   }

   public function getTotalViews() {
      $query = $this->db->prepare(
         "SELECT sum(views) FROM videos WHERE uploadedBy=:uploadedBy"
      );
      $query->bindParam(":uploadedBy", $this->username);
      $query->execute();

      return $query->fetchColumn();
    }

   // Array of usernames to which $this is subscribed
   public function subscriptionsArray($objects = false) {
      $query = $this->db->prepare(
         "SELECT toUsername FROM subscribes WHERE fromUsername=:fromUsername"
      );
      $query->bindParam(":fromUsername", $this->username);
      $query->execute();
      
      $subscribers = array();
      $subscriberObjs = array();

      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $user = new User($this->db, $row["toUsername"]);
         array_push($subscribers, $user->username);
         array_push($subscriberObjs, $user);
      }

      if ($objects) return $subscriberObjs;
      return $subscribers;
   }

}
?>
