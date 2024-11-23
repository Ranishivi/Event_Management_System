<?php
$servername = "localhost"; 
$port_no = 3306;  
$username = "r.shivika";
$password = "shivi@123";
$myDB= "event_management";

try {
    $conn = new PDO("mysql:host=$servername;port=$port_no;dbname=$myDB", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; 
   
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
