<?php
session_start();
include("inc/functions.php");
$product = getProduct();
$navBar = getNavBar();

if(!isset($_SESSION['cart'])) 
{
    $_SESSION['cart'] = [];
}

//empty the cart
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['emptyCart'])) 
{
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

//Add a product to the cart
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['productId'])) 
{
    // die("Dit is regelnummer " . __LINE__);
    $productId = intval($_POST['productId']);

        $_SESSION['cart'][$productId] = $_POST['productQuantity'];

    header("Location: cart.php");
    exit();
}

$totalPrice = 0;

if(!isset($_SESSION['loggedin'])|| $_SESSION['loggedin'] !== true) 
{
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styling/main.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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
<body class = "cart-container">
    <div class="container-cart"> 
        <h2 class = "cartName">Your Cart</h2>
        <?php
        if(!empty($_SESSION['cart']));
        ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_POST['update-quantity'])) 
                {
                    $productId = $_POST['update-quantity'];
                    $quantity = $_POST['quantity-' . $productId];
                    // Update the quantity in the cart
                    $_SESSION['cart'][$productId] = $quantity;
                }
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId']) && isset($_POST['productQuantity'])) 
                {
                    $productId = intval($_POST['productId']);
                    $productQuantity = intval($_POST['productQuantity']);
                    $_SESSION['cart'][$productId] = $productQuantity;
                    echo 'Product added to cart';
                    exit;
                }
                
                foreach($_SESSION['cart'] as $productId => $productQuantity) 
                {
                    $productDetails = getProductDetails($productId);
                    
                    if ($productId == 0 || $productQuantity == 0) 
                    {
                        continue;
                    }
                    if($productDetails)
                    { // voorkom errors bij ongeldige producten

                        $subTotal = ($productDetails['productPrice'] ?? 0) * $productQuantity;
                        $totalPrice += $subTotal;
                        ?>
                        <tr>
                            <td>
                                <?php echo $productId?>
                            </td>
                            <td>
                                <?php echo $productDetails["productName"]?>
                            </td>
                            <td class="cart_img"> 
                                <a href="productsdetails.php?page=product&id=<?php echo $productDetails['productId'] ?? ''?>">
                                <img src="<?php echo $productDetails['productImage']?>" alt="Product Image" width="100px" height="auto"/>
                                </a>
                            </td>
                        
                            <td class="price">&dollar; <?php echo $productDetails['productPrice']?></td>
                            <td class="quantity">
                                <form action="" method="post">
                                    <input type="hidden" name="productId" value="<?php echo $productId?>"/>
                                    <input type="number" name="quantity-<?php echo $productId?>" min="1" max="10" placeholder="<?php echo $productQuantity?>" required>
                                    <button type="submit" name="update-quantity" value="<?php echo $productId?>">Update</button>
                                </form>
                            </td>
                       
                            <td>&dollar; <?php echo $subTotal;?></td>
                            <td>
                                <form action = "cart.php" method = "post">
                                    <button type = "submit"> Delete Item</button>
                                    <input type = "hidden" name = "productId" value = "<?php echo $productId ?>">
                                </form>
                            </td>
                        </tr>
                        <?php 
                    } 
                    else 
                    { ?>
                       <tr>
                            <td colspan="7"> Product not found</td>
                       </tr>
            <?php  }
                }?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan = "7">Total Price: $ <?php echo number_format($totalPrice, 2, ",", ".");?></td>
                </tr>
                <tr>
                    <td colspan="7">
                        <form action = "" method = "post">
                            <a href="products.php">Continue Shopping</a>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="7">
                        <form action = "checkout.php" method = "post">
                            <?php foreach ($_SESSION['cart'] as $productId => $productQuantity) 
                            {?>
                                <input type="hidden" name="productId[]" value="<?php echo $productId; ?>">
                                <input type="hidden" name="productQuantity[]" value="<?php echo $productQuantity ?>">
                                <input type="hidden" name="productPrice[]" value="<?php echo $productDetails['productPrice']?>">
                                <input type="hidden" name="productName[]" value="<?php echo $productDetails['productName']?>">
                    <?php   } ?>
                            <button type = "submit">Place Order</button>
                        </form>
                    </td>
                   
                </tr>
            </tfoot>
        </table>
       
    </div>
</body>
</html>