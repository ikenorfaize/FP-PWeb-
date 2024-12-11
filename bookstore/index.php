<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css">
<body>
<?php
session_start();
    if(isset($_POST['ac'])){
        $servername = "localhost";
        $username = "root";
        $password = "";

        $conn = new mysqli($servername, $username, $password);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "USE bookstore";
        $conn->query($sql);

        $sql = "SELECT * FROM book WHERE BookID = '".$_POST['ac']."'";
        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()){
            $bookID = $row['BookID'];
            $quantity = $_POST['quantity'];
            $price = $row['Price'];
        }

        $sql = "INSERT INTO cart(BookID, Quantity, Price, TotalPrice) VALUES('".$bookID."', ".$quantity.", ".$price.", Price * Quantity)";
        $conn->query($sql);
    }

    $servername = "localhost";
    $username = "root";
    $password = "";

    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "USE bookstore";
    $conn->query($sql);

    $sql = "SELECT * FROM book";
    $result = $conn->query($sql);
?>

<?php
if(isset($_SESSION['id'])){
    echo '<header>';
    echo '<blockquote>';
    echo '<a href="index.php"><img src="image/book-logo.png" style="width: 100px; height: auto;"></a>';
    echo '<a href="cart.php" class="header-button"><i class="fa fa-shopping-cart" style="font-size:24px"></i></a>';
    echo '<form class="hf" action="logout.php"><input class="hi" type="submit" name="submitButton" value="Logout"></form>';
    echo '<form class="hf" action="edituser.php"><input class="hi" type="submit" name="submitButton" value="Edit Profile"></form>';
    echo '</blockquote>';
    echo '</header>';
}

if(!isset($_SESSION['id'])){
    echo '<header>';
    echo '<blockquote>';
    echo '<a href="index.php"><img src="image/book-logo.png" style="width: 100px; height: auto;"></a>';
    echo '<a href="cart.php" class="header-button"><i class="fa fa-shopping-cart" style="font-size:24px"></i></a>';
    echo '<form class="hf" action="Register.php"><input class="hi" type="submit" name="submitButton" value="Register"></form>';
    echo '<form class="hf" action="login.php"><input class="hi" type="submit" name="submitButton" value="Login"></form>';
    echo '</blockquote>';
    echo '</header>';
}

echo '<blockquote>';
echo "<table id='myTable' style='width:80%; margin: 0 auto;'>";
echo "<tr>";
while($row = $result->fetch_assoc()) {
    echo "<td style='width: 33%; padding: 10px;'>";
    echo "<table style='width: 100%;'>";
    echo '<tr><td><a href="bookdetails.php?BookID='.$row["BookID"].'"><img src="'.$row["Image"].'" width="80%" style="display: block; margin: 0 auto;"></a></td></tr>';
    echo '<tr><td style="padding: 5px; text-align: center;">'.$row["BookTitle"].'</td></tr>';
    echo '<tr><td style="padding: 5px; text-align: center;">By '.$row["Author"].'</td></tr>';
    echo '<tr><td style="padding: 5px; text-align: center;">Rp. '.$row["Price"].'</td></tr>';
    echo '<tr><td style="padding: 5px; text-align: center;">
    <form action="" method="post">
    Quantity: <input type="number" value="1" name="quantity" style="width: 30%;"/><br>
    <input type="hidden" value="'.$row['BookID'].'" name="ac"/>
    <input class="button" type="submit" value="Add to Cart"/>
    </form></td></tr>';
    echo "</table>";
    echo "</td>";
}
echo "</tr>";
echo "</table>";
echo '</blockquote>';
?>
</body>
</html>
