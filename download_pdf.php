<?php
session_start();
if (!isset($_GET['id'])) {
    echo "Invalid Book ID!";
    exit();
}
$book_id = $_GET['id'];

$host = 'localhost';     
$dbname = 'bestudent';
$username = 'root';      
$password = '';  

$error = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
$stmt = $conn->prepare("SELECT name, pdf_data FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$book) {
    echo "Book not found with ID: " . $book_id;
    exit();
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $book['name'] . '.pdf"');
echo $book['pdf_data']; 

$conn = null;
?>
