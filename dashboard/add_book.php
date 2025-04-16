<?php
session_start();
require_once "../config/database.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $year = $_POST["year"];
    $genre = $_POST["genre"];
    $quantity = $_POST["quantity"];
    $isbn = $_POST["isbn"];  

    if (!empty($title) && !empty($author) && !empty($year) && !empty($genre) && !empty($quantity) && !empty($isbn)) {
        try {
            $stmt = $conn->prepare("INSERT INTO books (title, author, year, genre, quantity, isbn, available) 
                                    VALUES (:title, :author, :year, :genre, :quantity, :isbn, :available)");

            $stmt->execute([
                "title" => $title,
                "author" => $author,
                "year" => $year,
                "genre" => $genre,
                "quantity" => $quantity,
                "isbn" => $isbn,          
                "available" => $quantity  
            ]);

            header("Location: view_books.php");
            exit();
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    } else {
        $error_message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
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
        <h2>Add Book</h2>

        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="title" placeholder="Book Title" required><br><br>
            <input type="text" name="author" placeholder="Author" required><br><br>
            <input type="number" name="year" placeholder="Year" required><br><br>
            <input type="text" name="genre" placeholder="Genre" required><br><br>
            <input type="number" name="quantity" placeholder="Quantity Available" required><br><br>
            <input type="text" name="isbn" placeholder="ISBN" required><br><br> <!-- Add ISBN field -->
            <button type="submit">Add Book</button>
        </form>
    </div>
</body>
</html>
