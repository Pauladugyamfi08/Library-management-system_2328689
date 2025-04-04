<?php
session_start();
if ($_SESSION["role"] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../static/style.css"> 
</head>
<body class="user-dashboard">
    <div class="user-container">
        <h2>Welcome to Your Dashboard</h2>

        <!-- User Navigation Buttons -->
        <div class="user-nav">
            <a href="../user/borrow_book.php">Borrow a Book</a>
            <a href="../user/return_book.php">Return a Book</a>
            <a href="../user/search_book.php">Search Books</a>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Library Management System. All rights reserved. |
    </div>
</body>
</html>

