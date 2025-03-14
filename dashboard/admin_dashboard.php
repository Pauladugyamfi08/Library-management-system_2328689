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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../static/style.css"> 
    <link rel="stylesheet" href="../static/admin.css"> 
</head>
<body class="admin-dashboard">
    <div class="admin-container">
        <h2>Admin Dashboard</h2>

        <div class="admin-nav">
            <a href="../admin/manage_users.php">Manage Users</a>
            <a href="../admin/view_books.php">Manage Books</a>
            <a href="../auth/logout.php">Logout</a>
        </div>

        <div class="admin-actions">
            <button onclick="window.location.href='../admin/add_book.php'">Add New Book</button>
            <button onclick="window.location.href='../admin/view_books.php'">View All Books</button>
        </div>
    </div>
</body>
</html>
