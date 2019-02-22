<?php
require_once("../includes/config.php");

if (isset($_POST['videoId']) && isset($_POST['thumbnailId'])) {
    $videoId = $_POST['videoId'];
    $thumbnailId = $_POST['thumbnailId'];

    $query = $db->prepare(
       "UPDATE thumbnails SET selected=0 WHERE videoId=:videoId"
      );
    $query->bindParam(":videoId", $videoId);
    $query->execute();

    $query = $db->prepare(
       "UPDATE thumbnails SET selected=1 WHERE id=:thumbnailId"
      );
    $query->bindParam(":thumbnailId", $thumbnailId);
    $query->execute();
}
?>