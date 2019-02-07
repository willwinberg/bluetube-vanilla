<?php
ob_start();
session_start();
date_default_timezone_set("America/Denver");

try {
    $dbConnection = new PDO("mysql:dbname=bluetube;host=localhost", "root", "");
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}

catch (PDOException $error) {
    echo "Database connection Error: " . $error->getMessage();
}
?>