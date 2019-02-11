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

   public function getReplies() {
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

   public function like() {
      $id = $this->getId();
      $username = $this->userLoggedInObj->getUsername();

      if($this->wasLikedBy()) {
         // User has already liked
         $query = $this->db->prepare("DELETE FROM likes WHERE username=:username AND commentId=:commentId");
         $query->bindParam(":username", $username);
         $query->bindParam(":commentId", $id);
         $query->execute();

         return -1;
      }
      else {
         $query = $this->db->prepare("DELETE FROM dislikes WHERE username=:username AND commentId=:commentId");
         $query->bindParam(":username", $username);
         $query->bindParam(":commentId", $id);
         $query->execute();
         $count = $query->rowCount();

         $query = $this->db->prepare("INSERT INTO likes(username, commentId) VALUES(:username, :commentId)");
         $query->bindParam(":username", $username);
         $query->bindParam(":commentId", $id);
         $query->execute();

         return 1 + $count;
      }
   }
   
   public function wasLikedBy() {
      $query = $this->db->prepare("SELECT * FROM likes WHERE username=:username AND commentId=:commentId");
      $query->bindParam(":username", $username);
      $query->bindParam(":commentId", $id);

      $id = $this->getId();

      $username = $this->userLoggedInObj->getUsername();
      $query->execute();

      return $query->rowCount() > 0;
   }

   public function dislike() {
      $id = $this->getId();
      $username = $this->userLoggedInObj->getUsername();

      if($this->wasDislikedBy()) {
         // User has already liked
         $query = $this->db->prepare("DELETE FROM dislikes WHERE username=:username AND commentId=:commentId");
         $query->bindParam(":username", $username);
         $query->bindParam(":commentId", $id);
         $query->execute();

         return 1;
      }
      else {
         $query = $this->db->prepare("DELETE FROM likes WHERE username=:username AND commentId=:commentId");
         $query->bindParam(":username", $username);
         $query->bindParam(":commentId", $id);
         $query->execute();
         $count = $query->rowCount();

         $query = $this->db->prepare("INSERT INTO dislikes(username, commentId) VALUES(:username, :commentId)");
         $query->bindParam(":username", $username);
         $query->bindParam(":commentId", $id);
         $query->execute();

         return -1 - $count;
      }
   }

   public function wasDislikedBy() {
      $query = $this->db->prepare("SELECT * FROM dislikes WHERE username=:username AND commentId=:commentId");
      $query->bindParam(":username", $username);
      $query->bindParam(":commentId", $id);
      
      $id = $this->getId();
      
      $username = $this->userLoggedInObj->getUsername();
      $query->execute();
      
      return $query->rowCount() > 0;
   }

   function time_elapsed_string($datetime, $full = false) {
      $now = new DateTime;
      $ago = new DateTime($datetime);
      $diff = $now->diff($ago);
   
      $diff->w = floor($diff->d / 7);
      $diff->d -= $diff->w * 7;
   
      $string = array(
         'y' => 'year',
         'm' => 'month',
         'w' => 'week',
         'd' => 'day',
         'h' => 'hour',
         'i' => 'minute',
         's' => 'second',
      );
      foreach ($string as $k => &$v) {
         if ($diff->$k) {
               $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
         } else {
               unset($string[$k]);
         }
      }
   
      if (!$full) $string = array_slice($string, 0, 1);
      return $string ? implode(', ', $string) . ' ago' : 'just now';
   }

}
?>