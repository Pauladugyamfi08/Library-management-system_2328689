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
    <link rel="stylesheet" href="../static/user_style.css"> 
</head>
<body class="user-dashboard">
    <div class="user-container">
        <h2>Welcome to Your Dashboard</h2>

       
        <div class="user-nav">
            <a href="../user/borrow_book.php">Borrow a Book</a>
            <a href="../user/return_book.php">Return a Book</a>
            <a href="../user/search_book.php">Search Books</a>
            <a href="../auth/logout.php">Logout</a>
        </div>

        
        <div class="borrow-section">
            <h3>Click Here To Borrow</h3>
            <button class="borrow-btn" onclick="window.location.href='../user/borrow_book.php'">Start Borrowing</button>
        </div>
    </div>
</body>
</html>

