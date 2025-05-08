<?php
session_start();
include("inc/functions.php");
$navBar = getNavBar();
$blogcategories = getBlogCategories();
$blogCategoryId = $_GET['blogCategoryId'] ?? null;
$blogs = getBlog($blogCategoryId);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get the form data
    $blogTitle = $_POST["blogTitle"];
    $blogAuthor = $_POST["Author"];
    $blogCategoryId = $_POST["blogCategory"];
    $blogPost = $_POST["blogContent"];
  
    // connect to the database
    $conn = mysqli_connect("localhost", "root", "", "GameWorld");
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
  
    // insert the data into the database
    $sql = "INSERT INTO blog (blogTitle, blogAuthor, blogPost, blogDate, blogCategoryId) VALUES ('$blogTitle', '$blogAuthor', '$blogPost' , NOW(), $blogCategoryId)";
    if ($conn->query($sql)=== TRUE){
      header("Location: blog.php");
      echo "Thank you for contacting us!";
    }else{
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styling/main.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
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
<body>
    <aside class="right-sidebar">
        <h1>Category</h1>
        <ul>
            <?php
            foreach($blogcategories as $blogcategory) 
            {
            ?>
                <li><a href="blog.php?blogCategoryId=<?php echo $blogcategory["blogCategoryId"] ?>"><?php echo $blogcategory["blogCategoryName"] ?></a></li>
            <?php
            }
            ?>
        </ul>
        <div class="modal fade" id="blog-form-modal" tabindex="-1" role="dialog" aria-labelledby="blog-form-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blog-form-modal-label">Create New Blog Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="blog.php" method="post">
                        <div class="form-group">
                            <label for="blog-title">Title</label>
                            <input type="text" class="form-control" name="blogTitle" placeholder="Enter blog title">
                        </div>
                        <div>
                            <label>Author</label>
                            <input type="text" class="form-control" name="Author" placeholder="Enter your author name">
                        </div> 
                        <div class="form-group">
                            <label for="blog-category">Category</label>
                            <select class="form-control" name="blogCategory">
                                <?php
                                $blogcategories = getBlogCategories();
                                foreach($blogcategories as $blogCategory) {
                                ?>
                                    <option value="<?php echo $blogCategory["blogCategoryId"] ?>"><?php echo $blogCategory["blogCategoryName"] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="blog-content">Content</label>
                            <textarea class="form-control" name="blogContent" placeholder="Enter blog content"></textarea>
                        </div>
                       
                        <button type="submit" class="btn btn-primary">Create Blog Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </aside>
    
        <?php if(isset($_GET["blogCategoryId"]))
        {
            $blogCategoryId = $_GET['blogCategoryId'];
            foreach($blogs as $blog) 
            { 
            ?>
            <article class="blog">
                <h3><?php echo $blog["blogTitle"] ?></h3>
                <h4><?php echo $blog["blogAuthor"]?></h4>
                <p><?php echo $blog["blogPost"]?></p>
                <form action="Blog.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name">
                <br>
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment"></textarea>
                <br>
                <input type="submit" value="Submit">
            </form>
            <?php 
            if (isset($_POST["comment"])) 
            { ?>
                <div class="comment">
                    <h5><?php echo $_POST["name"] ?></h5>
                    <p><?php echo $_POST["comment"] ?></p>
                </div>
     <?php  } ?>
            </article>
       <?php } 
       } else 
       {
        
        foreach($blogs as $blog) 
        { 
            ?>
           <article class="blog">
                <h3><?php echo $blog["blogTitle"] ?></h3>
                <h4><?php echo $blog["blogAuthor"]?></h4>
                <p><?php echo $blog["blogPost"]?></p>
                <label for="name">Name:</label>
                <form>
                <input type="text" id="name" name="name">
                <br>
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment"></textarea>
                <br>
                <input type="submit" value="Submit">
            </form>
            <?php if (isset($_POST["comment"])) { ?>
                <div class="comment">
                    <h5><?php echo $_POST["name"] ?></h5>
                    <p><?php echo $_POST["comment"] ?></p>
                </div>
            <?php } ?>
            </article>

<?php   } 
        }?>
</body>
</html>