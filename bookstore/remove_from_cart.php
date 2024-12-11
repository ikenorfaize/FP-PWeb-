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

if (isset($_GET['CartID'])) {
    $cartID = $_GET['CartID'];

    $sqlCheckCart = "SELECT * FROM Cart WHERE CartID = $cartID AND CustomerID = $customerID";
    $resultCheck = $conn->query($sqlCheckCart);

    if ($resultCheck->num_rows > 0) {
        $sqlDelete = "DELETE FROM Cart WHERE CartID = $cartID";
        if ($conn->query($sqlDelete) === TRUE) {
            header("Location: cart.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Cart item not found or you don't have permission to delete this item.";
    }
} else {
    echo "CartID not specified.";
}

$conn->close();
?>
