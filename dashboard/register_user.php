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
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($password)) {
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
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$first_name, $last_name, $email, $hashed_password, 'user']); // Default role is 'user'

            // Redirect to the admin dashboard after successful registration
            header("Location: admin_dashboard.php");
            exit();
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
    <a href="admin_dashboard.php" class="home-btn">Home</a>

    <div class="container">
        <h2>Register New User</h2>
        <?php if (!empty($message)): ?>
            <p style="color: <?= strpos($message, 'successfully') !== false ? 'green' : 'red'; ?>;">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>
        <form method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" required><br><br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br><br>

            <button type="submit">Register User</button>
        </form>
        <p><a href="admin_dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>