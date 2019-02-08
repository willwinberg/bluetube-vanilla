<?php
ob_start();
session_start();
date_default_timezone_set("America/Denver");

define('IS_LIVE', 'localhost' != $_SERVER['HTTP_HOST']);

if (IS_LIVE) {
    $dbName = "";
    $host = "";
    $root = "";
    $password = "";
} else {
    $dbName = "bluetube";
    $host = "localhost";
    $root = "root";
    $password = "ambros1a";
}

try {
    $dbConnection = new PDO("mysql:dbname=$dbName;host=$host", "$root", "$password");
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}

catch (PDOException $error) {
    echo "Database connection Error: " . $error->getMessage();
}
?>