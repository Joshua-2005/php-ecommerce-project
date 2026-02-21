<?php
/*
    DATABASE CONFIGURATION
    Update these values before running locally.
*/

$host = "localhost";
$dbname = "your_database_name";
$user = "your_username";
$password = "your_password";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed.");
}
?>
