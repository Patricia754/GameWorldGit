<?php
session_start();
include("inc/functions.php");
$navBar = getNavBar();

$totalPrice = 0;
$totalQuantity = 0;
//check if the user is logged in
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) 
{
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(isset ($_POST['placeorder']))
    {
        if(count($_SESSION['cart']) > 0) 
        {

            //connection with the database
            $conn = database();
            // get the userId from the session key user
            $userId = intval($_SESSION['user']['userId']);

            //step 1: saving the order in the 'orders' table
            $sql = "INSERT INTO `order` (orderDate, userId) VALUES (NOW(), ?)";
            $stmt = $conn -> prepare($sql);
            $stmt -> bind_param('i', $userId);
            $stmt -> execute();
            $orderId = $stmt -> insert_id; //get the generated orderId
            $stmt -> close();

            //close database connection
            $conn->close();

            //empty cart after order
            unset($_SESSION['cart']);
            //redirect to it self
            header("location: checkout.php");
            exit;
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styling/main.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<header>
<nav>
    <ul>
        <?php
        foreach($navBar as $navBaritem) 
        {
        ?>
            <?php if($navBaritem["navName"] == "Products") { ?>
                <li class="dropdown">
                    <a href="products.php" class="dropdown-toggle"><?php echo $navBaritem["navName"] ?></a>
                    <ul class="dropdown-menu">
                        <?php
                        $categories = getCategories();
                        foreach($categories as $category) 
                        {
                        ?>
                            <li><a href="products.php?categoryId=<?php echo $category["categoryId"] ?>"><?php echo $category["categoryName"] ?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
                <?php } elseif($navBaritem["navName"] == "Login") { 
                    if(isset($_SESSION['user']))
                    { 
                        ?>
                         <li class="dropdown">
                            <a href="profile.php" class="dropdown-toggle">
                                <img src="database/img/profileIcon.png" alt="Avatar" class="avatar">
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href = "Download.php?file=files/books.jpg">Download</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php
                    } 
                    else{?>
                    <li class="dropdown">
                        <a href="login.php" class="dropdown-toggle"><?php echo $navBaritem["navName"] ?></a>
                        <ul class="dropdown-menu">
                            <li><a href = "login.php">login</a></li>
                            <li><a href="signup.php">Sign Up</a></li>
                        </ul>
                    </li>
            <?php   }
        } else { ?>
                <li><a href="<?php echo $navBaritem["navURL"] ?>"><?php echo $navBaritem["navName"] ?></a></li>
            <?php } ?>
        <?php
        }
        ?>
    </ul>
</nav>
</header>
<body class="cart-container">
   <div class = "container-cart">
        <h2>Order Summary</h2>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php 

            foreach ($_SESSION['cart'] as $productId => $productQuantity) 
            { 
                $productDetails = getProductDetails($productId); ?>
                <tr>
                    <td><?php echo $productDetails['productName']?></td>
                    <td><?php echo $productQuantity?></td>
                    <td><?php echo $productDetails['productPrice']?></td>
                </tr>
                <?php $subTotal = ($productDetails['productPrice'] ?? 0) * $productQuantity;
            $totalPrice += $subTotal; 
            $totalQuantity += $productQuantity; ?>
           <?php 
            }
            
            ?>
            <tr>
                <td><strong>TotalPrice</strong></td>
                <td><?php echo $totalQuantity; ?></td>
                <td>&dollar; <?php echo $totalPrice;?></td>
            </tr>
        </table>
        <form action="checkout.php" method="post">
            <input type="submit" name="placeorder" value="Place Order">
        </form>
   </div>
</body>
</html>
<?php
?>