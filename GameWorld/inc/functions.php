<?php

function database() 
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gameworld";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function getCategories() 
{
    $conn = database();
    $sql = "SELECT * FROM categories";
    $resource = $conn->query($sql) or die($conn->error);
    $categories = $resource->fetch_all(MYSQLI_ASSOC);
    return $categories;
}

function getProducts($categoryId = null)
{
    $conn = database();
    if ($categoryId !== null) {
        $sql = "SELECT * FROM products WHERE categoryId = $categoryId";
    } else {
        $sql = "SELECT * FROM products";
    }
    $resource = $conn->query($sql) or die($conn->error);
    $products = $resource->fetch_all(MYSQLI_ASSOC);
    return $products;
}

function getProduct() 
{
  if (!isset($productId) || empty($productId)) 
  {
      return array(); // or return an error message
  }

  $conn = database();
  $sql = "SELECT * FROM products WHERE productId = $productId";
  $resource = $conn->query($sql) or die($conn->error);
  $product = $resource->fetch_all(MYSQLI_ASSOC);
  return $product;
}


function getSingelProduct() 
{
    $conn = database();
    if(isset($_GET['categoryId']) && is_numeric($_GET['categoryId'])) {
        $sql = 'SELECT * FROM products WHERE categoryId = '. $_GET['categoryId'];
    } 
    $resource = $conn->query($sql) or die($conn->error);
    $singleProduct = $resource->fetch_assoc();
    return $singleProduct;
}

function getNavBar() 
{
  $conn = database();
  $sql = 'SELECT *  FROM navbar';
  $resource = $conn->query($sql) or die($conn->error);
  $navBar =  $resource->fetch_all(MYSQLI_ASSOC);
  return $navBar;
}

function getProductDetails($productId) {
    $conn = database();
    $sql = "SELECT * FROM products WHERE productId = $productId";
    $resource = $conn->query($sql) or die($conn->error);
    $productDetails = $resource->fetch_assoc();
    return $productDetails;
}

function getLogo() {
    $conn = database();
    $sql = "SELECT * FROM logo";
    $resource = $conn->query($sql) or die($conn->error);
    $logo = $resource->fetch_all(MYSQLI_ASSOC);
    return $logo;
}

function signup()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $uname = $_POST["uname"];
        $email = $_POST["email"];
        $psw = $_POST["psw"];
    
        $conn = mysqli_connect("localhost", "root", "", "gameworld");
         if (!$conn) 
         {
           die("Connection failed: " . mysqli_connect_error());
         }
    
         $hash = password_hash($psw, PASSWORD_DEFAULT);
         $sql = "INSERT INTO users (userFname, userLname, userName, userEmail, userPassword) VALUES ('$fname', '$lname', '$uname', '$email', '$hash')";
         $result = mysqli_query($conn, $sql);
         if ($result)
         {
           echo "<script>alert('You have successfully signed up!'); window.location.href = 'login.php';</script>";
         }
         else
         {
           echo "Error: " . $sql . "<br>" . $conn->error;
         }
         $conn->close();
       
    
    }
}

function dd($var, $die = false)
{
  echo "<pre>";
  var_dump($var);
  echo "</pre>";

  if($die)
  {
    die("Done dumping");
  }
}

function login()
{
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uname = $_POST["uname"];
        $psw = $_POST["psw"];
    
        $conn = mysqli_connect("localhost", "root", "", "gameworld");
         if (!$conn) 
         {
           die("Connection failed: " . mysqli_connect_error());
         }
    
         $sql = "SELECT * FROM users WHERE userName = '$uname'";
         $result = mysqli_query($conn, $sql);
         $user = mysqli_fetch_assoc($result);
         if ($user && password_verify($psw, $user['userPassword'])) 
         {

          // set loggedIn to true
          $_SESSION['loggedin'] = true;
          // set user info in array as well
          $_SESSION['user'] = [
            'userId' => $user['userId'],
            'userName' => $user['userName'],
          ];
           echo "<script>alert('You have successfully logged in!'); window.location.href = 'index.php';</script>";
         }
         else
         {
           echo "<script>alert('Invalid username or password. Please try again.'); window.location.href = 'login.php';</script>";
         }
         $conn->close();
    }
}

function getBlogCategories() 
{
  $conn = database();
    $sql = "SELECT * FROM blogcategory";
    $resource = $conn->query($sql) or die($conn->error);
    $blogcategories = $resource->fetch_all(MYSQLI_ASSOC);
    return $blogcategories;
}

function getBlog($blogCategoryId = null)
{
    $conn = database();
    if ($blogCategoryId !== null) {
        $sql = "SELECT * FROM blog WHERE blogCategoryId = $blogCategoryId";
    } else {
        $sql = "SELECT * FROM blog";
    }
    $resource = $conn->query($sql) or die($conn->error);
    $blogs = $resource->fetch_all(MYSQLI_ASSOC);
    return $blogs;
}
?>