<?php
// start or resume existing session
session_start();

//get the functions
require "inc/functions.php";	

//dd($_POST);
//check if there is a shoppingcart
//if not, create one
if(!isset($_SESSION['cart'])) 
{ 
    $_SESSION['cart'] = []; // empty array
}

// check if there is something posted
if($_SERVER['REQUEST_METHOD'] == "POST") 
{
    $_SESSION['cart'][$_POST['productId']] = $_POST['quantity'];
}

//reload to cart.php
header("Location: cart.php");
exit();

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['productId']))	
{
    $productId = intval($_POST['productId']);
    if(!isset($_SESSION['cart'][$productId])) 
    {
        $_SESSION['cart'][$productId] = 1;
    }
    else 
    {
        $_SESSION['cart'][$productId] ++;
    }
    
    unset($_SESSION['order_placed']);//verwijder de blokkering voor een nieuwe bestelling
}
?>