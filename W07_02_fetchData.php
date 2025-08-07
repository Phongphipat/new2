<?php

require_once 'W07_01_ConnectDB.php';

// Fetch data from the database 
$sql = "SELECT * FROM products";

$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    // Output data of each row
    // echo "<H1>พบข้อมูล ใน ตาราง products</H1><br>";

    // $data = $result->fetchAll(PDO::FETCH_NUM);
    // $data = $result->fetchAll(PDO::FETCH_ASSOC);
    // $data = $result->fetchAll(PDO::FETCH_BOTH);
    // // foreach ($data as $row) {
    //     echo "ID: " . $row[0] . ", Name: " . $row[1] . "<br>";
    // }

    //     print_r($data); 

    // }
//===========================================================================================================================

    //ใช้ prepared statement
//ใช้execute() เพื่อป้องกัน SQL Injection
//ใช้ fetchALL() เพื่อดึงข้อมูลทั้งหมด
// ใช้print_r() เพื่อแสดงข้อมูล


    // $stmt = $conn->prepare($sql);
// $stmt->execute();
//  $data = $stmt->fetchAll(PDO::FETCH_NUM);
// echo "<br>";
// echo "<pre>";
//  print_r($data); 
//  echo"</pre>";

    //  echo "<hr>";
//===========================================================================================================================

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo "<br>";
// echo "<pre>";
//  print_r($data); 
//  echo"</pre>";

    //  echo "<hr>";
//===========================================================================================================================

    // แสดงผลข้อมูลที่ดึงมา json
    header('Content-Type: application/json'); // ระบุ Content-Type เป็น JSON
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); // แปลงข้อมูลใน $arr เป็น JSON และแสดงผล




} else {
    echo "ไม่พบข้อมูล ใน ตาราง products<br>";
}



?>