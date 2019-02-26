<?php

// Set up the database connection
$dsn = 'mysql:host=localhost;dbname=record_store_db';
$username = 'danish';
$password = 'admin';
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    echo $error_message;
}