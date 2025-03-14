<?php
require_once "../config/database.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"]; // User's input email
    $password = $_POST["password"]; // User's input password
    $role = $_POST["role"]; // Role selected by the user (either 'admin' or 'user')

    // Validate role selection (only allow 'admin' or 'user')
    if ($role !== 'admin' && $role !== 'user') {
        echo "Invalid role selected. Please select either 'admin' or 'user'.";
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

    // Insert user into the database with the selected role and email
    try {
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
        $stmt->execute([
            "email" => $email,
            "password" => $hashed_password,
            "role" => $role
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
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>

           
            <label for="role">Select Role:</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select><br><br>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
