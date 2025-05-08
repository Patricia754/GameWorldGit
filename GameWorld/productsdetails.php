
<?php
include("inc/functions.php");
session_start();
if(isset($_POST["addToCart"])) 
{
    $productId = $_POST['productId'];
    $product_Quantity = $_POST['productQuantity'];
}

$productId = $_GET["productId"];
$productDetails = getProductDetails($productId);
$navBar = getNavBar();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styling/main.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>productDetails</title>
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
                    if($_SESSION['user']) 
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
<body>
    <div class="product-details">
        <h1 class="product-name"><?php echo $productDetails["productName"] ?></h1>
        <img src="<?php echo $productDetails["productImage"]; ?>" alt="product image">
        <div class="price-container">
            <p>Price: $<?php echo $productDetails["productPrice"]?></p>
        </div>
        <div class = "quantity-container">
            <form action="cart.php" method="POST">
                <label for="productQuantity">Quantity:</label>
                <input type="number" id="productQuantity" name="productQuantity" min="1" max="10" value="<?php echo $productQuantity; ?>"/>
                <input type="hidden" name="productId" value="<?php echo $productDetails['productId']?>"/>
                <button type="submit" value="Add to Cart">Add to Cart</button>
            </form>
        </div> 
        <p><?php echo $productDetails["productDetails"] ?></p>
    </div>
</body> 
</html>