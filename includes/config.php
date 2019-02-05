<?php
ob_start();
date_default_timezone_set("America/Denver");

try {
    $connection = new PDO("mysql:dbname=bluetube;host=localhost", "root", "");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}

catch (PDOException $error) {
    echo "Connection Error: " . $error->getMessage();
}
?>