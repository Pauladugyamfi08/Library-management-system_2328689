<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = $_POST["role"];

    if ($password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo "This email is already registered. Please choose another one.";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (:first_name, :last_name, :email, :password, :role)");
        $stmt->execute([
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "password" => $hashed_password,
            "role" => $role
        ]);
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" placeholder="First Name" required><br><br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" placeholder="Last Name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Password" required><br><br>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required><br><br>

            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select><br><br>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
