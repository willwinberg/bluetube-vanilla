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

   public function likeVideo($video) {
      if (in_array($this->username, $video->getLikedUsernameArray())) {
         $query = $this->db->prepare(
            "DELETE FROM likes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();

         $result = array("likes" => -1, "dislikes" => 0);

         return json_encode($result);
      }
      else {
         $query = $this->db->prepare(
         "DELETE FROM dislikes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();

         $count = $query->rowCount();
         
         $query = $this->db->prepare(
            "INSERT INTO likes (username, videoId) VALUES(:username,   :videoId)"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();  

         $result = array("likes" => 1, "dislikes" => 0 - $count);

         return json_encode($result);
      }
   }

   public function dislikeVideo($video) {
      if (in_array($this->username, $video->getDislikedUsernameArray())) {
         $query = $this->db->prepare(
            "DELETE FROM dislikes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();

         $result = array("dislikes" => -1, "likes" => 0);

         return json_encode($result);
      }
      else {
         $query = $this->db->prepare(
         "DELETE FROM likes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();

         $count = $query->rowCount();
         
         $query = $this->db->prepare(
            "INSERT INTO dislikes (username, videoId) VALUES(:username, :videoId)"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute(); 

         $result = array("dislikes" => 1, "likes" => 0 - $count);

         return json_encode($result);
      }
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
