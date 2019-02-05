<?php
ob_start();
date_default_timezone_set("America/Denver");

try {
    $dB_Connection = new PDO("mysql:dbname=bluetube;host=localhost", "root", "");
    $dB_Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}

catch (PDOException $error) {
    echo "Database connection Error: " . $error->getMessage();
}
?>