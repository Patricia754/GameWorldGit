<?php
include("inc/functions.php");
$navBar = getNavBar();
login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styling/main.css">
    <title>Login</title>
</head>
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
<body class = "login_body">
    <form action="login.php" method="post">

        <div class = "login"> 
            <label for = "uname"><b>UserName</b></label>
            <input type = "text" placeholder = "Enter Username" name="uname" required>

            <label for = "psw"><b>Password</b></label>
            <input type = "password" placeholder = "Enter Password" name = "psw" required>

            <button type = "submit">Login</button>
            <label>
                <input type = "checkbox" checked = "checked" name = "remember"> Remember me
            </label>
        </div>

        <div>
            <button type = "button" class = "cancelbtn">Cancel</button>
            <span class = "psw">forgot<a href="#">password?</a></span>
        </div>
    </form>
    <button class="signupbtn" onclick="window.location.href='signup.php'">Sign Up</button>
</body>
</html>