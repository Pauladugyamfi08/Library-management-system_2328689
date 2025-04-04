<?php
session_start();
include '../config/database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

   
    if (!empty($email) && !empty($password) && !empty($role)) {
       
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
           
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            
            if ($role == 'admin') {
                header("Location: ../dashboard/admin_dashboard.php");
            } else {
                header("Location: ../dashboard/user_dashboard.php");
            }
            exit();
        } else {
            // If no user found or password is incorrect
            $message = "Invalid email, password, or role selection.";
        }
    } else {
        // If some fields are missing
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library System</title>
    <link rel="stylesheet" href="../static/style.css">

</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($message): ?>
            <p class="error"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="role">Login as:</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="admin"> Admin</option>
                <option value="user"> User</option>
            </select>
            
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p><a href="reset_password.php">Forgot Password?</a></p>
    </div>
</body>
</html>
