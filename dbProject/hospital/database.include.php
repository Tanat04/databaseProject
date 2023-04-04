<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbmsProject";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>