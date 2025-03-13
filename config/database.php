<?php
$host ='localhost';
$user ='root';
$password ='';
$database ='srccapstoneproject';
$port = 3306;

$connection = mysqli_connect($host, $user, $password, $database, $port);

if (mysqli_connect_error()){

    echo "Error: unable to connect to MySQL <br>";
    echo "message:".mysqli_connect_error()."<br>";
} 
?>

 <!-- // $servername = "localhost";
// $username = "root"; // Replace with your database username
// $password = ""; // Replace with your database password
// $dbname = "rsstudentregistration"; // Replace with your database name
// $port = 3306;

// // Create connection
// $connection = new mysqli($servername, $username, $password, $dbname, $port);

// // Check connection
// if ($connection->connect_error) {
//     die("Connection failed: " . $connection->connect_error);
// }  -->


<!-- $host = "localhost"; // Change if your database is on a different server
$username = "root";  // Change if you have a different DB username
$password = "";      // Change if your DB has a password
$database = "srccapstoneproject"; // Replace with your actual database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}  -->






<!-- $servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "srccapstoneproject"; // Replace with your actual DB name
$port = 3306; // MySQL default port

// Create connection
$connection = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
 -->
