<?php

// class Subscribe {

//    public $id, $toUsername, $fromUsername;

//    public function __constructor($db, $input) {
//       if (is_array($input)) {
//          $subscribe = $input;
//       } else {
//          $query = $this->db->prepare(
//             "SELECT * FROM subscribes WHERE id = :id"
//          );
//          $query->bindParam(":id", $input);
//          $query->execute();

//          $subscribe = $query->fetch(PDO::FETCH_ASSOC);

//          $this->id = $subscribe["id"];
//          $this->toUsername = $subscribe["toUsername"];
//          $this->fromUsername = $subscribe["fromUsername"];
//       }
//    }

// }

?>