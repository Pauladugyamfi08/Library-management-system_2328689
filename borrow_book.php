<?php 
session_start();
require_once "../config/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    die("You must be logged in to borrow a book.");
}

$success_message = '';  
$error_message = '';    

// Fetch available books for the dropdown
$stmt = $conn->query("SELECT * FROM books WHERE available > 0"); // Ensure that the 'available' column is greater than 0
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle book borrowing when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book_id"])) {
    $book_id = $_POST["book_id"];
    $user_id = $_SESSION["user_id"];

    try {
        // Check if the user has already borrowed this book
        $stmt = $conn->prepare("SELECT * FROM borrowed_books WHERE user_id = :user_id AND book_id = :book_id");
        $stmt->execute(["user_id" => $user_id, "book_id" => $book_id]);
        $already_borrowed = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($already_borrowed) {
            // User has already borrowed this book
            $error_message = "You have already borrowed this book. Please choose a different book.";
        } else {
            // Insert into borrowed_books table
            $stmt = $conn->prepare("INSERT INTO borrowed_books (user_id, book_id) VALUES (:user_id, :book_id)");
            $stmt->execute(["user_id" => $user_id, "book_id" => $book_id]);

            // Update book status to unavailable (decrement available count)
            $stmt = $conn->prepare("UPDATE books SET available = available - 1 WHERE id = :book_id AND available > 0");
            $stmt->execute(["book_id" => $book_id]);

            // Set success message
            $success_message = "You have successfully borrowed the book!";
        }
    } catch (PDOException $e) {
        // Handle errors
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body>
    <div class="container">
        <h2>Borrow a Book</h2>

        <?php if (!empty($success_message)): ?>
            <p style="color: green; font-weight: bold;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p style="color: red; font-weight: bold;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="book_id">Select a book:</label>
            <select name="book_id" id="book_id" required>
                <option value="" disabled selected>-- Choose a book --</option>
                
                <?php if (count($books) > 0): ?>
                    <?php foreach ($books as $book): ?>
                        <option value="<?= $book['id'] ?>"><?= htmlspecialchars($book['title']) ?> by <?= htmlspecialchars($book['author']) ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No available books</option>
                <?php endif; ?>
            </select><br><br>

            <button type="submit" class="borrow-btn">Borrow</button>
        </form>
    </div>
</body>
</html>
