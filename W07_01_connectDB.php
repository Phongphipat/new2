<?php
//connect database ด้วย mysqli
$host = "localhost"; // ชื่อโฮสต์
$username = "root"; // ชื่อผู้ใช้
$password = ""; // รหัสผ่าน
$database = "d68s_product"; // ชื่อฐานข้อมูล




// $conn = new mysqli($host, $username, $password, $database);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// } else {
// echo "Connected successfully to the database: $database";
// }
$dns = "mysql:host=$host;dbname=$database;charset=utf8mb4"; // Data Source Name (DSN) for PDO
try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo " PD0 Connected successfully ";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


?>