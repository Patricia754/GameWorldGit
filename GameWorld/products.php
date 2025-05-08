<?php
session_start();
include("inc/functions.php");
$categoryId = $_GET['categoryId'] ?? null;
$products = getProducts($categoryId);
$navBar = getNavBar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styling/main.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameWorld</title>
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
<body>
    <div class="container">
   
            <?php
            foreach($products as $product) 
            {
            ?>
            <div class="products">
                <a href="productsdetails.php?productId=<?php echo $product["productId"]; ?>">
                <img src="<?php echo $product["productImage"]; ?>" alt="product image">
                </a>
                <h3><?php echo $product["productName"] ?></h3>
                <p>Price: $<?php echo $product["productPrice"] ?></p>
                <form action = "cart.php" method = "post">
                    <input type="hidden" name="productId" value="<?php echo $product["productId"]; ?>">
                    <input type="number" name="productQuantity" value="1" min="1" max="10">
                    <button type="submit" value="Add to Cart" onclick="return confirm('Add this item to the cart?')">Buy Now</button>
                </form>
            </div>
            <?php
            }
            ?>
        
    </div>
</body>
</html>