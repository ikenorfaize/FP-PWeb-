<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
<link rel="stylesheet" href="style.css">
<body>
<?php
session_start();

if (isset($_GET['BookID'])) {
    $bookID = $_GET['BookID'];

    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "USE bookstore";
    $conn->query($sql);

    $sql = "SELECT * FROM book WHERE BookID = '" . $bookID . "'";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        echo '<header>';
        echo '<blockquote>';
        echo '<a href="index.php"><img src="image/book-logo.png" style="width: 100px; height: auto;"></a>';
        echo '</blockquote>';
        echo '</header>';

        echo '<div style="width: 80%; margin: 0 auto; text-align: left;">';
        echo '<img src="' . $row['Image'] . '" style="width: 30%; float: left; margin-right: 20px;">';
        echo '<h1>' . $row['BookTitle'] . '</h1>';
        echo '<p><strong>Author:</strong> ' . $row['Author'] . '</p>';
        echo '<p><strong>ISBN:</strong> ' . $row['ISBN'] . '</p>';
        echo '<p><strong>Type:</strong> ' . $row['Type'] . '</p>';
        echo '<p><strong>Price:</strong> Rp. ' . number_format($row['Price'], 2, ',', '.') . '</p>';
        echo '<form action="" method="post">';
        echo '<input type="hidden" value="' . $row['BookID'] . '" name="ac">';
        echo 'Quantity: <input type="number" value="1" name="quantity" style="width: 10%;"><br><br>';
        echo '<input class="button" type="submit" value="Add to Cart">';
        echo '</form>';
        echo '</div>';
    } else {
        echo "<p>Book not found.</p>";
    }
} else {
    echo "<p>Invalid book selection.</p>";
}
?>
</body>
</html>
