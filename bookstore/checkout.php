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

$totalCost = 0;
$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $totalCost += $row['TotalPrice'];
}

if ($totalCost <= 0) {
    echo "Your cart is empty.";
    exit();
}

// $apiKey = "YOUR_API_KEY";
// $apiUrl = "https://api.snap.bankindonesia.or.id/v1/transaction";  // This is an example URL

// $requestData = [
//     'amount' => $totalCost,
//     'order_id' => uniqid('order_'),
//     'description' => "Payment for Books",
//     'customer' => [
//         'name' => $customer['CustomerName'],
//         'email' => $customer['CustomerEmail'],
//         'phone' => $customer['CustomerPhone'],
//         'address' => $customer['CustomerAddress']
//     ]
// ];

// $ch = curl_init($apiUrl);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_HTTPHEADER, [
//     'Authorization: Bearer ' . $apiKey,
//     'Content-Type: application/json'
// ]);
// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

// $response = curl_exec($ch);
// curl_close($ch);

// $responseData = json_decode($response, true);

// if (isset($responseData['status']) && $responseData['status'] == 'success') {
//     $snapToken = $responseData['token'];

//     header("Location: https://payment.snap.bankindonesia.or.id/checkout?token=" . $snapToken);
//     exit();
// } else {
//     echo "Error: Unable to process payment. Please try again later.";
// }

$conn->close();
?>
