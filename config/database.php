<?php
$host = "localhost";
$dbname = "library_db";  // MUST MATCH YOUR DATABASE NAME!
$username = "root";  // Default MySQL user in XAMPP
$password = "";  // Leave blank for XAMPP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
echo "yhuuhjhnuybyhuhuybyu";
?>
