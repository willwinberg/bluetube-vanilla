<?php
ob_start();
session_start();
date_default_timezone_set("America/Denver");
// date_default_timezone_set("America/Los_Angeles");

$dbName = "blue_tube";
$host = "localhost";
$root = "root";
$password = "";

try {
    $db = new PDO("mysql:dbname=$dbName;host=$host", "$root", "$password");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}

catch (PDOException $error) {
    echo "Database connection Error: " . $error->getMessage();
}
?>