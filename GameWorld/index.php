<?php
session_start();
include("inc/functions.php");
$categories = getCategories();
$navBar = getNavBar();
$logo = getLogo();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styling/main.css">
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
    <div class = "logo">
        <img src = "<?php print_r($logo[0]['logoImage']) ?>">
    </div>
    <div class="container">
        <div class=" text">
            <h1>Welcome to GameWorld</h1>
         </div>
        <div class="categories">
            <?php
            foreach($categories as $category)
            {
             ?>
                 <a href='products.php?categoryId=<?php echo $category["categoryId"];?> '><img src="<?php echo $category["categoryImage"]; ?>"/></a>
            <?php
            }
            ?>
        </div>
    </div>
</body>
</html>