<?php

class Video {

   protected $db, $video, $user;

   public 
      $id,
      $tile,
      $description,
      $privacy,
      $category,
      $views,
      $duration,
      $filePath,
      $uploadedBy,
      $uploadedDate
   ;

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

      $this->id = $video["id"];
      $this->title = $video["title"];
      $this->description = $video["description"];
      $this->privacy = $video["privacy"];
      $this->category = $video["category"];
      $this->views = $video["views"];
      $this->duration = $video["duration"];
      $this->filePath = $video["filePath"];
      $this->uploadedBy = $video["uploadedBy"];
      $this->uploadDate = $video["uploadDate"];
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
   
   // public function views() {
   //    $video["views"];
   // }
   
   // public function duration() {

   // }
   
   // public function filePath() {

   // }
   
   public function getUploadDate() {
      return date("M j, Y", strtotime($this->uploadDate));
   }

   public function timestamp() {
      return date("M jS, Y", strtotime($this->uploadDate));
   }

   public function incrementViews() {
      $query = $this->db->prepare(
         "UPDATE videos SET views=views+1 WHERE id=:id"
      );
      $query->bindParam(":id", $this->id);
      $query->execute();

      $this->views++;
   }

   public function addLike() {
      if (in_array($this->user->username, $this->getLikedUsernameArray())) {
         $query = $this->db->prepare(
            "DELETE FROM likes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":videoId", $this->id);
         $query->execute();

         $result = array("likes" => -1, "dislikes" => 0);

         return json_encode($result);
      }
      else {
         $query = $this->db->prepare(
         "DELETE FROM dislikes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":videoId", $this->id);
         $query->execute();

         $count = $query->rowCount();
         
         $query = $this->db->prepare(
            "INSERT INTO likes (username, videoId) VALUES(:username,   :videoId)"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":videoId", $this->id);
         $query->execute();  

         $result = array("likes" => 1, "dislikes" => 0 - $count);

         return json_encode($result);
      }
   }

   public function addDislike() {
      if (in_array($this->user->username, $this->getDislikedUsernameArray())) {
         $query = $this->db->prepare(
            "DELETE FROM dislikes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":videoId", $this->id);
         $query->execute();

         $result = array("dislikes" => -1, "likes" => 0);

         return json_encode($result);
      }
      else {
         $query = $this->db->prepare(
         "DELETE FROM likes WHERE username=:username AND videoId=:videoId"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":videoId", $this->id);
         $query->execute();

         $count = $query->rowCount();
         
         $query = $this->db->prepare(
            "INSERT INTO dislikes (username, videoId) VALUES(:username, :videoId)"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":videoId", $this->id);
         $query->execute(); 

         $result = array("dislikes" => 1, "likes" => 0 - $count);

         return json_encode($result);
      }
   }

   function getLikedUsernameArray() {
      $query = $this->db->prepare(
         "SELECT * FROM likes WHERE videoId = :videoId"
      );
      $query->bindParam(":videoId", $this->id);
      $query->execute();

      $users = $query->fetchAll();
      $array = array();
      
      foreach ($users as $user) {
         array_push($array, $user["username"]);
      }

      return $array;
   }

   public function getDislikedUsernameArray() {
      $query = $this->db->prepare(
         "SELECT * FROM dislikes WHERE videoId = :videoId"
      );
      $query->bindParam(":videoId", $this->id);
      $query->execute();

      $users = $query->fetchAll();
      $array = array();
      
      foreach ($users as $user) {
         array_push($array, $user["username"]);
      }

      return $array;
   }

   public function getCommentCount() {
      $query = $this->db->prepare(
         "SELECT * FROM comments WHERE videoId=:videoId"
      );
      $query->bindParam(":videoId", $this->id);
      $query->execute();

      return $query->rowCount();
   }

   public function getCommentsArray() {
      $query = $this->db->prepare(
         "SELECT * FROM comments WHERE videoId=:videoId AND replyTo=0 ORDER BY postDate DESC"
      );
      $query->bindParam(":videoId", $this->id);
      $query->execute();
      $comments = array();

      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $comment = new CommentCard($this->db, $row, $this->user, $this->id);
         array_push($comments, $comment);
      }

      return $comments;
   }

   public function getThumbnails($single = true) {
      $getOne = $single ? "AND selected=1" : "";
      $query = $this->db->prepare(
         "SELECT * FROM thumbnails WHERE videoId=:videoId $getOne");
      $query->bindParam(":videoId", $this->id);
      $query->execute();
      
      if ($single) {
         return $query->fetch();
      } else {
         return $query->fetchAll();
      }
   }
   
   public function dataSameAs($data) {
      $details = array(
         $this->title(),
         $this->description(),
         $this->privacy(),
         $this->category()
      );

      return sizeof(array_intersect($data, $details)) > 0;
   }
   
   
}