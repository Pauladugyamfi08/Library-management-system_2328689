<?php
session_start();
require_once "../config/database.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$users = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../static/style.css">
    <style>
        .home-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .home-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Home Button -->
    <a href="../dashboard/admin_dashboard.php" class="home-btn">Home</a>

    <div class="container">
        <h2>Manage Users</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user["id"] ?></td>
                    <td><?= $user["email"] ?></td>
                    <td><?= $user["role"] ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a> | 
                        <a href="delete_users.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
