<?php
require_once "../config/database.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"]; // User's first name
    $last_name = $_POST["last_name"]; // User's last name
    $email = $_POST["email"]; // User's email
    $password = $_POST["password"]; // User's password
    $confirm_password = $_POST["confirm_password"]; // Confirm password

    // Validate that passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match. Please try again.";
        exit;
    }

    // Check if the email already exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo "This email is already registered. Please choose another one.";
        exit;
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database with the provided details
    try {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (:first_name, :last_name, :email, :password, :role)");
        $stmt->execute([
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "password" => $hashed_password,
            "role" => 'user' // Default role is 'user'
        ]);
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } catch (PDOException $e) {
        // Catch the exception and show an error message
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

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
