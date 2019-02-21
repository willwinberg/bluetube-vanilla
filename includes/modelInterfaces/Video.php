<?php

class Video {

   protected $db, $video, $user;

   public function __construct($db, $input, $user) {
      $this->db = $db;
      $this->user = $user;

      if (is_array($input)) {
         $video = $input;
      } else {
         $query = $this->db->prepare(
            "SELECT * FROM videos WHERE id = :id"
         );
         $query->bindParam(":id", $input);
         $query->execute();

         $video = $query->fetch(PDO::FETCH_ASSOC);
      }

      $this->video = $video;
      $this->expanded = false;
   }

   public function id() {
      return $this->video["id"];
   }
   
   public function title() {
      return $this->video["title"];
   }
   
   public function description() {
      return $this->video["description"];
   }
   
   public function privacy() {
      return $this->video["privacy"];
   }
   
   public function category() {
      return $this->video["category"];
   }
   
   public function views() {
      return $this->video["views"];
   }
   
   public function duration() {
      return $this->video["duration"];
   }
   
   public function filePath() {
      return $this->video["filePath"];
   }

   public function uploadedBy() {
      return $this->video["uploadedBy"];
   }
   
   public function uploadDate() {
      return date("M j, Y", strtotime($this->video["uploadDate"]));
   }

   public function timestamp() {
      return date("M jS, Y", strtotime($this->video["uploadDate"]));
   }

   public function incrementViews() {
      $id = $this->id();

      $query = $this->db->prepare(
         "UPDATE videos SET views=views+1 WHERE id=:id"
      );
      $query->bindParam(":id", $id);
      $query->execute();

      $this->video["views"] = $this->video["views"] + 1;
   }

   public function addLike() {
      $id = $this->id();
      $username = $this->user->username();

      if (in_array($username, $this->getLikedUsernameArray())) {
         $query = $this->db->prepare(
            "DELETE FROM likes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $username);
         $query->bindParam(":videoId", $id);
         $query->execute();

         $result = array("likes" => -1, "dislikes" => 0);

         return json_encode($result);
      }
      else {
         $query = $this->db->prepare(
         "DELETE FROM dislikes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $username);
         $query->bindParam(":videoId", $id);
         $query->execute();

         $count = $query->rowCount();
         
         $query = $this->db->prepare(
            "INSERT INTO likes (username, videoId) VALUES(:username,   :videoId)"
         );
         $query->bindParam(":username", $username);
         $query->bindParam(":videoId", $id);
         $query->execute();  

         $result = array("likes" => 1, "dislikes" => 0 - $count);

         return json_encode($result);
      }
   }

   public function addDislike() {
      $id = $this->id();
      $username = $this->user->username();

      if (in_array($username, $this->getDislikedUsernameArray())) {
         $query = $this->db->prepare(
            "DELETE FROM dislikes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $username);
         $query->bindParam(":videoId", $id);
         $query->execute();

         $result = array("dislikes" => -1, "likes" => 0);

         return json_encode($result);
      }
      else {
         $query = $this->db->prepare(
         "DELETE FROM likes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $username);
         $query->bindParam(":videoId", $id);
         $query->execute();

         $count = $query->rowCount();
         
         $query = $this->db->prepare(
            "INSERT INTO dislikes (username, videoId) VALUES(:username, :videoId)"
         );
         $query->bindParam(":username", $username);
         $query->bindParam(":videoId", $id);
         $query->execute(); 

         $result = array("dislikes" => 1, "likes" => 0 - $count);

         return json_encode($result);
      }
   }

   function getLikedUsernameArray() {
      $id = $this->id();

      $query = $this->db->prepare(
         "SELECT * FROM likes WHERE videoId = :videoId"
      );
      $query->bindParam(":videoId", $id);
      $query->execute();

      $users = $query->fetchAll();
      $array = array();
      
      foreach ($users as $user) {
         array_push($array, $user["username"]);
      }

      return $array;
   }

   public function getDislikedUsernameArray() {
      $id = $this->id();

      $query = $this->db->prepare(
         "SELECT * FROM dislikes WHERE videoId = :videoId"
      );
      $query->bindParam(":videoId", $id);
      $query->execute();

      $users = $query->fetchAll();
      $array = array();
      
      foreach ($users as $user) {
         array_push($array, $user["username"]);
      }

      return $array;
   }

   public function getCommentCount() {
      $id = $this->id();

      $query = $this->db->prepare(
         "SELECT count(*) FROM comments WHERE videoId=:videoId"
      );
      $query->bindParam(":videoId", $id);
      $query->execute();

      return $query->fetchColumn();
   }

   public function getCommentsArray() {
      $id = $this->id();

      $query = $this->db->prepare(
         "SELECT * FROM comments WHERE videoId=:videoId AND replyTo=0 ORDER BY postDate DESC"
      );
      $query->bindParam(":videoId", $id);
      $query->execute();
      $comments = array();

      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $comment = new CommentCard($this->db, $row, $this->user, $id);
         array_push($comments, $comment);
      }

      return $comments;
   }

   public function getThumbnails($selected = false) {
      $id = $this->id();

      $getOne = $selected ? "AND selected=1" : "";
      $query = $this->db->prepare(
         "SELECT * FROM thumbnails WHERE videoId=:videoId $getOne");
      $query->bindParam(":videoId", $id);
      $query->execute();
      
      if ($selected) {
         return $query->fetch();
      } else {
         return $query->fetchAll();
      }
   }

   public function getDetailsArray() {
      return array(
         "title" => $this->title(),
         "description" => $this->description(),
         "privacy" => $this->privacy(),
         "category" => $this->category(),
      );
   }
   
}