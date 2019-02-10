<?php

class User {

   private $dbConnection;
   public $firstName, $lastName, $username, $email, $image;

   public function __construct($dbConnection, $username) {
      $this->dbConnection = $dbConnection;

      $query = $this->dbConnection->prepare(
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
         $query = $this->dbConnection->prepare(
            "DELETE FROM likes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();

         $result = array("likes" => -1, "dislikes" => 0);

         return json_encode($result);
      }
      else {
         $query = $this->dbConnection->prepare(
         "DELETE FROM dislikes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();

         $count = $query->rowCount();
         
         $query = $this->dbConnection->prepare(
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
         $query = $this->dbConnection->prepare(
            "DELETE FROM dislikes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();

         $result = array("dislikes" => -1, "likes" => 0);

         return json_encode($result);
      }
      else {
         $query = $this->dbConnection->prepare(
         "DELETE FROM likes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();

         $count = $query->rowCount();
         
         $query = $this->dbConnection->prepare(
            "INSERT INTO dislikes (username, videoId) VALUES(:username, :videoId)"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute(); 

         $result = array("dislikes" => 1, "likes" => 0 - $count);

         return json_encode($result);
      }
   }

   public function subscriberCount() {
      $query = $this->dbConnection->prepare(
         "SELECT * FROM subscribers WHERE to=:to"
      );
      $query->bindParam(":to", $this->username);
      $query->execute();
      return $query->rowCount();
   }

   public function subscriptionsArray() {
      $query = $this->dbConnection->prepare(
         "SELECT to FROM subscribers WHERE userFrom=:from"
      );
      $query->bindParam(":from", $this->username);
      $query->execute();
      
      $subscribers = array();

      foreach ($query->fetchAll() as $row) {
         $subscriber = new User($this->dbConnection, $row["to"]);
         array_push($subscribers, $subscriber);
      }
      return $subscribers;
   }

}
?>
