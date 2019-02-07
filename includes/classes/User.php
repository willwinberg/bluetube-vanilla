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
}
?>
