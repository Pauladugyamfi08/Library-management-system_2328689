<?php
session_start();
require_once "../config/database.php";

// Check if the user is an admin
if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($username) && !empty($email) && !empty($password) && !empty($role)) {
        // Check if the email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_user) {
            $message = "A user with this email already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $role]);

            $message = "User registered successfully!";
        }
    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body>
    <div class="container">
        <h2>Register New User</h2>
        <?php if (!empty($message)): ?>
            <p style="color: <?= strpos($message, 'successfully') !== false ? 'green' : 'red'; ?>;">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br><br>

            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select><br><br>

            <button type="submit">Register User</button>
        </form>
        <p><a href="manage_users.php">Back to Manage Users</a></p>
    </div>
</body>
</html>