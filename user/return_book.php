<?php
session_start();
require_once "../config/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_id"])) {
    die("You must be logged in to return a book.");
}

$user_id = $_SESSION["user_id"];

// Fetch books that the user has borrowed
$stmt = $conn->prepare("SELECT books.id, books.title, books.author FROM borrowed_books 
                        JOIN books ON borrowed_books.book_id = books.id 
                        WHERE borrowed_books.user_id = :user_id");
$stmt->execute(["user_id" => $user_id]);
$borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book_id"])) {
    $book_id = $_POST["book_id"];

    // Delete from borrowed_books table
    $stmt = $conn->prepare("DELETE FROM borrowed_books WHERE user_id = :user_id AND book_id = :book_id");
    $stmt->execute(["user_id" => $user_id, "book_id" => $book_id]);

    // Update book availability
    $stmt = $conn->prepare("UPDATE books SET available = 1 WHERE id = :book_id");
    $stmt->execute(["book_id" => $book_id]);

    echo "<p style='color: green;'>Book returned successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Return Book</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body>
    <div class="container">
        <h2>Return a Book</h2>
        <?php if (empty($borrowed_books)): ?>
            <p>You have not borrowed any books.</p>
        <?php else: ?>
            <form method="POST">
                <select name="book_id" required>
                    <?php foreach ($borrowed_books as $book): ?>
                        <option value="<?= $book['id'] ?>"><?= $book['title'] ?> by <?= $book['author'] ?></option>
                    <?php endforeach; ?>
                </select><br><br>
                <button type="submit">Return</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
