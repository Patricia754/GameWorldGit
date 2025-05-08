<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // get the form data
  $name = $_POST["name"];
  $email = $_POST["email"];
  $message = $_POST["message"];

  // connect to the database
  $conn = mysqli_connect("localhost", "root", "", "GameWorld");
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // insert the data into the database
  $sql = "INSERT INTO contact (contactName, contactEmail, contactMessage, contactDate) VALUES ('$name', '$email', '$message' , NOW())";
  if ($conn->query($sql)=== TRUE){
    header("Location: contact.php");
    echo "Thank you for contacting us!";
  }else{
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $conn->close();
}
?>