<?php
ob_start();
session_start();
date_default_timezone_set("America/Denver");
// date_default_timezone_set("America/Los_Angeles");

$host = "localhost";
$dbName = "blue_tube";
$root = "root";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbName", "$root");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}

catch (PDOException $error) {
    echo "Database connection Error: " . $error->getMessage();
}
?>