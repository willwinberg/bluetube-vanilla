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
   
   public function getFullName() {
      return $this->firstName . " " . $this->lastName;
   }

   public static function isLoggedIn() {
      return isset($_SESSION["loggedIn"]);
   }

   public function likeVideo($video) {
      if (in_array($this->username, $video->likedArray)) {
         $query = $this->dbConnection->prepare(
            "DELETE FROM likes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();

         return json_encode(
            array(
               "likes" => -1,
               "dislikes" => 0
            )
        );
      }
      else {
         $query = $this->dbConnection->prepare(
         "DELETE FROM dislikes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();
         
         $query = $this->dbConnection->prepare(
            "INSERT INTO likes (username, videoId) VALUES(:username,   :videoId)"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();  

         return json_encode(
            array(
               "likes" => 1,
               "dislikes" => -1
            )
         );
      }
   }

   public function dislikeVideo($video) {
      if (in_array($this->username, $video->dislikedArray)) {
         $query = $this->dbConnection->prepare(
            "DELETE FROM dislikes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();

         return json_encode(
            array(
               "dislikes" => -1,
               "likes" => 0
            )
        );
      }
      else {
         $query = $this->dbConnection->prepare(
         "DELETE FROM likes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();
         
         $query = $this->dbConnection->prepare(
            "INSERT INTO dislikes (username, videoId) VALUES(:username, :videoId)"
         );
         $query->bindParam(":username", $this->username);
         $query->bindParam(":videoId", $video->id);
         $query->execute();  

         return json_encode(
            array(
               "dislikes" => 1,
               "likes" => -1
            )
         );
      }
   }

}
?>
