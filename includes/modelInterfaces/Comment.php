<?php 

class Comment {

   private $db, $comment, $user, $videoId;

   public function __construct($db, $input, $user, $videoId) {
      if (is_array($input)) {
         $comment = $input;
      } else {
         $query = $db->prepare(
            "SELECT * FROM comments where id=:id"
         );
         $query->bindParam(":id", $input);
         $query->execute();

         $comment = $query->fetch(PDO::FETCH_ASSOC);
      }
      
      $this->db = $db;
      $this->comment = $comment;
      $this->user = $user;
      $this->videoId = $videoId;
   }
   
   public function id() {
      return $this->comment["id"];
   }
   
   public function videoId() {
      return $this->videoId;
   }

   public function postedBy() {
      return $this->comment["postedBy"];
   }

   public function getRepliesArray() {
      $id = $this->getId();
      $query = $this->db->prepare(
         "SELECT * FROM comments WHERE responseTo=:commentId ORDER BY datePosted ASC"
      );
      $query->bindParam(":commentId", $id);
      $query->execute();

      $videoId = $this->videoId();
      $comments = array();

      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $comment = new Comment($this->db, $row, $this->user, $videoId);
         array_push($comments, $comment);
      }

      return $comments;
   }

   function getReplyCount() {
      $id = $this->comment->id();

      $query = $db->prepare(
         "SELECT count(*) FROM comments WHERE responseTo=:responseTo"
      );
      $query->bindParam(':responseTo', $id);

      return $query->fetchColumn();
   }

   public function totalLikes() {
      $id = $this->id();

      $query = $this->db->prepare(
         "SELECT * FROM likes WHERE commentId=:commentId"
      );
      $query->bindParam(":commentId", $id);
      $query->execute();

      $likeCount = $query->rowCount();

      $query = $this->db->prepare(
         "SELECT * FROM dislikes WHERE commentId=:commentId"
      );
      $query->bindParam(":commentId", $id);
      $query->execute();

      $dislikeCount = $query.rowCount();
      
      return $likeCount - $dislikeCount;
    }

   public function addLike() {
      if (in_array($this->user->username, $this->usersWhoLikedArray())) {
         $query = $this->db->prepare(
            "DELETE FROM likes WHERE username=:username AND commentId=:commentId"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":commentId", $this->id);
         $query->execute();

         return -1;
      } else {
         $query = $this->db->prepare(
            "DELETE FROM dislikes WHERE username=:username AND commentId=:commentId"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":commentId", $this->id);
         $query->execute();
         $count = $query->rowCount();

         $query = $this->db->prepare(
            "INSERT INTO likes (username, commentId) VALUES (:username, :commentId)"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":commentId", $this->id);
         $query->execute();

         return 1 + $count;
      }
   }
   
   public function usersWhoLikedArray() {
     $query = $this->db->prepare(
         "SELECT * FROM likes WHERE username=:username AND commentId=:commentId"
      );
      $query->bindParam(":username", $this->user->username);
      $query->bindParam(":commentId", $this->id);
      $query->execute();
      $users = $query->fetchAll();
      $array = array();

      foreach ($users as $user) {
         array_push($array, $user["username"]);
      }

      return $array;
   }

   public function addDislike() {
      if (in_array($this->user->username, $this->usersWhoDislikedArray())) {
         $query = $this->db->prepare(
            "DELETE FROM dislikes WHERE username=:username AND commentId=:commentId"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":commentId", $this->id);
         $query->execute();

         return -1;
      } else {
         $query = $this->db->prepare(
            "DELETE FROM likes WHERE username=:username AND commentId=:commentId"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":commentId", $this->id);
         $query->execute();
         $count = $query->rowCount();

         $query = $this->db->prepare(
            "INSERT INTO dislikes (username, commentId) VALUES (:username, :commentId)"
         );
         $query->bindParam(":username", $this->user->username);
         $query->bindParam(":commentId", $this->id);
         $query->execute();

         return 1 + $count;
      }
   }
   
   public function usersWhoDislikedArray() {
     $query = $this->db->prepare(
         "SELECT * FROM dislikes WHERE username=:username AND commentId=:commentId"
      );
      $query->bindParam(":username", $this->user->username);
      $query->bindParam(":commentId", $this->id);
      $query->execute();
      $users = $query->fetchAll();
      $array = array();

      foreach ($users as $user) {
         array_push($array, $user["username"]);
      }

      return $array;
   }


}
?>