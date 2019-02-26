<?php

class User {

   protected $db, $user;

   public function __construct($db, $username) {
      $query = $db->prepare(
         "SELECT * FROM users WHERE username = :username"
      );
      $query->bindParam(":username", $username);
      $query->execute();

      $user = $query->fetch(PDO::FETCH_ASSOC);

      $this->db = $db;
      $this->user = $user;
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
      // EDIT
      return "assets/images/banners/default-banner.png";
   }

   public function signUpDate() {
        $date = $this->user["signUpDate"];

        return date("F jS, Y", strtotime($date));
   }

   public function basicDataArray() {
      $array = array(
         "firstName" => $this->firstName(),
         "lastName" => $this->lastName(),
         "email" => $this->email(),
         "emailConfirm" => $this->email(),
      );
      
      return $array;
   }
   
   public function getSubscriberCount() {
      $username = $this->username();

      $query = $this->db->prepare(
         "SELECT * FROM subscribes WHERE toUsername=:toUsername"
      );
      $query->bindParam(":toUsername", $username);
      $query->execute();
      return $query->rowCount();
   }

   public function getTotalViews() {
      $username = $this->username();

      $query = $this->db->prepare(
         "SELECT sum(views) FROM videos WHERE uploadedBy=:uploadedBy"
      );
      $query->bindParam(":uploadedBy", $username);
      $query->execute();

      return $query->fetchColumn();
   }

   // Array of usernames to which $this is subscribed
   public function subscriptionsArray($objects = false) {
      $username = $this->username();
      
      $query = $this->db->prepare(
         "SELECT toUsername FROM subscribes WHERE fromUsername=:fromUsername"
      );
      $query->bindParam(":fromUsername", $username);
      $query->execute();
      
      $subscribers = array();
      $subscriberObjs = array();

      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $user = new User($this->db, $row["toUsername"]);
         $subscribers[] = $user->username();
         $subscriberObjs[] = $user;
      }

      if ($objects) return $subscriberObjs;
      return $subscribers;
   }

   public function updateDetails($data) {
      $username = $this->username();

      $query = $this->db->prepare(
         "UPDATE users SET
         firstName=:firstName,
         lastName=:lastName,
         email=:email WHERE
         username=:username"
      );
      $query->bindParam(":firstName", $data["firstName"]);
      $query->bindParam(":lastName", $data["lastName"]);
      $query->bindParam(":email", $data["email"]);
      $query->bindParam(":username", $username);
      $query->execute();

      if ($query->rowCount() === 1) {
         return Success::$detailsUpdate;
      } else {
         return Error::$detailsUpdate;
      }
   }

   public function updatePassword($password) {
      $username = $this->username();
      $password = hash("sha256", $password);

      $query = $this->db->prepare(
         "UPDATE users SET password=:password WHERE username=:username");
      $query->bindParam(":password", $password);
      $query->bindParam(":username", $username);
      $success = $query->execute();

      if ($query->rowCount() === 1) {
         return Success::$passwordUpdate;
      } else {
         return Error::$passwordUpdate;
      }
   }

   public function subscribe($toUsername) {
      $username = $this->username();

      $query = $this->db->prepare(
         "INSERT INTO subscribes (toUsername, fromUsername) VALUES (:toUsername, :fromUsername)"
      );
      $query->bindParam(":toUsername", $toUsername);
      $query->bindParam(":fromUsername", $username);
      $query->execute();
      return null;
   }

   public function unSubscribe($toUsername) {
      $username = $this->username();

      $query = $this->db->prepare(
         "DELETE FROM subscribes WHERE (toUsername=:toUsername AND fromUsername=:fromUsername)"
      );
      $query->bindParam(":toUsername", $toUsername);
      $query->bindParam(":fromUsername", $username);
      $query->execute();
      return null;
   }

   public function postComment($videoId, $replyTo, $body) {
      $postedBy = $this->username();

      $query = $this->db->prepare(
      "INSERT INTO comments (postedBy, videoId, replyTo, body)
      VALUES (:postedBy, :videoId, :replyTo, :body)"
      );
      $query->bindParam(":postedBy", $postedBy);
      $query->bindParam(":videoId", $videoId);
      $query->bindParam(":replyTo", $replyTo);
      $query->bindParam(":body", $body);

      $query->execute();

      return $this->db->lastInsertId();
   }

}
?>

