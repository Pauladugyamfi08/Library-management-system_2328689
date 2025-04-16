<?php
session_start();
require_once "../config/database.php";

// Default search query and filter
$search_query = "";
$filter = "title"; // Default filter to search by title

// Check if the form is submitted and process search
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $filter = $_GET['filter'];
}

// Prepare the SQL query for searching based on the filter (title, author, genre)
$sql = "SELECT * FROM books WHERE $filter LIKE :search_query AND quantity > 0";
$stmt = $conn->prepare($sql);
$stmt->execute(['search_query' => '%' . $search_query . '%']);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books - Library System</title>
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
    <a href="../dashboard/user_dashboard.php" class="home-btn">Home</a>

    <div class="search-container">
        <h2>Search Books</h2>

        <form method="GET">
            <label for="filter">Search by:</label>
            <select name="filter" id="filter" class="search-select">
                <option value="title" <?php echo ($filter == "title") ? 'selected' : ''; ?>>Title</option>
                <option value="author" <?php echo ($filter == "author") ? 'selected' : ''; ?>>Author</option>
                <option value="genre" <?php echo ($filter == "genre") ? 'selected' : ''; ?>>Genre</option>
            </select>

            <input type="text" name="search" class="search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>" required>
            <button type="submit" class="search-btn">Search</button>
        </form>

        <div class="no-results" <?php echo empty($books) ? 'style="display:block;"' : 'style="display:none;"'; ?>>
            <p>No available books.</p>
        </div>
    </div>

    <div class="books-list" <?php echo !empty($books) ? 'style="display:block;"' : 'style="display:none;"'; ?>>
        <?php if (count($books) > 0): ?>
            <?php foreach ($books as $book): ?>
                <div class="book-item">
                    <h4><?php echo htmlspecialchars($book['title']); ?></h4>
                    <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                    <p>Year: <?php echo htmlspecialchars($book['year']); ?></p>
                    <p>Genre: <?php echo htmlspecialchars($book['genre']); ?></p>
                    <p>Available Quantity: <?php echo $book['quantity']; ?></p>
                    
                    <form action="borrow_book.php" method="POST">
                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                        <button type="submit" class="borrow-btn">Borrow</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
