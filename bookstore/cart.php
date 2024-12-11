<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$conn = new mysqli($servername, $username, $password, "bookstore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION['id'];

$sqlCustomer = "SELECT CustomerID FROM Customer WHERE UserID = $userID";
$resultCustomer = $conn->query($sqlCustomer);

if ($resultCustomer->num_rows > 0) {
    $customer = $resultCustomer->fetch_assoc();
    $customerID = $customer['CustomerID'];
} else {
    echo "Customer not found.";
    exit();
}

$sql = "SELECT c.CartID, b.BookTitle, b.Author, b.Price, c.Quantity, c.TotalPrice 
        FROM Cart c
        JOIN Book b ON c.BookID = b.BookID
        WHERE c.CustomerID = $customerID";
$result = $conn->query($sql);
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <center>
    <?php
    if (isset($_SESSION['id'])) {
        echo '<header>';
        echo '<a href="index.php"><img src="image/logo.png" class="book-logo"></a>';
        echo '<div class="header-buttons">';
        echo '<a href="cart.php" class="header-button"><i class="fa fa-shopping-cart" style="font-size:24px"></i></a>';
        echo '<form class="hf" action="logout.php"><input class="hi" type="submit" name="submitButton" value="Logout"></form>';
        echo '<form class="hf" action="edituser.php"><input class="hi" type="submit" name="submitButton" value="Edit Profile"></form>';
        echo '</div>';
        echo '</header>';
    }
    ?>

    <div class="container">
        <h2>Your Cart</h2>

        <?php
        if ($result->num_rows > 0) {
            echo "<table id='cartTable' style='width:100%;'>";
            echo "<tr><th>Book Title</th><th>Author</th><th>Price</th><th>Quantity</th><th>Total Price</th><th>Action</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['BookTitle'] . "</td>";
                echo "<td>" . $row['Author'] . "</td>";
                echo "<td>Rp. " . number_format($row['Price'], 2, ',', '.') . "</td>";
                echo "<td>" . $row['Quantity'] . "</td>";
                echo "<td>Rp. " . number_format($row['TotalPrice'], 2, ',', '.') . "</td>";
                echo "<td><a href='remove_from_cart.php?CartID=" . $row['CartID'] . "'>Remove</a></td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Your cart is empty.</p>";
        }

        $sqlTotal = "SELECT SUM(c.TotalPrice) AS TotalCost 
                     FROM Cart c 
                     WHERE c.CustomerID = $customerID";
        $resultTotal = $conn->query($sqlTotal);
        $totalRow = $resultTotal->fetch_assoc();
        echo "<h3>Total: Rp. " . number_format($totalRow['TotalCost'], 2, ',', '.') . "</h3>";
        ?>
        
        <a href="checkout.php" class="button">Proceed to Checkout</a>
    </div>
    </center>
</body>
</html>

<?php
$conn->close();
?>
