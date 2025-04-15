<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root'; // Change to your MySQL username
$db_pass = '';     // Change to your MySQL password
$db_name = 'bestudent';

// Create connection
try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}