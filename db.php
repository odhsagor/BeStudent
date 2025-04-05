<?php
$host = 'localhost';     // or 127.0.0.1
$dbname = 'bestudent';
$username = 'root';      // use your MySQL username
$password = '';          // use your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
