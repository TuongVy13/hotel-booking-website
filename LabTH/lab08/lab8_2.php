<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
</head>
<body>
    <form method="post" action="search_books.php">
        <label for="book_name">Enter book name:</label>
        <input type="text" id="book_name" name="book_name" required>
        <input type="submit" value="Search">
    </form>
</body>
</html>
<?php
include_once "db.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the book name from the form input
    $search = $_POST['book_name'];

    // Prepare the SQL query with a LIKE clause
    $sql = "SELECT * FROM book WHERE book_name LIKE :book_name";
    $stm = $pdh->prepare($sql);
    $stm->bindValue(":book_name", "%$search%");
    $stm->execute();

    // Fetch all the matching rows
    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

    // Check if any results were found
    if (count($rows) > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Book ID</th><th>Book Name</th><th>Description</th><th>Price</th><th>Publisher ID</th><th>Category ID</th></tr>";
        
        // Display the results in a table
        foreach ($rows as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['book_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['book_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price']) . "</td>";
            echo "<td>" . htmlspecialchars($row['pub_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['cat_id']) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No books found matching your search.</p>";
    }
}
?>
