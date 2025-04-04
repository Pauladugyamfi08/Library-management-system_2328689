<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../static/admin_dashboard.css">
</head>
<body>
    <header>
        <div class="top-left">
            <button onclick="window.location.href='register_user.php'" class="register-btn">Register User</button>
        </div>
        <h1>Admin Dashboard</h1>
        <div class="logout-container">
            <a href="../auth/logout.php" class="logout-btn">Logout</a>
        </div>
    </header>
    <div class="admin-container">
        <h2>Welcome, Admin</h2>
        <div class="admin-nav">
            <a href="manage_users.php">Manage Users</a>
            <a href="view_books.php">Manage Books</a>
        </div>
        <div class="admin-actions">
            <button onclick="window.location.href='add_book.php'">Add New Book</button>
            <button onclick="window.location.href='view_books.php'">View All Books</button>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Library Management System</p>
    </footer>
</body>
</html>
