<?php
$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    // Output data of each row
    echo "<H2>พบข้อมูล ใน ตาราง products</H2><br>";
    $data = $result->fetchAll(PDO::FETCH_ASSOC);

    print_r($data);

    // $rows = $result->fetchAll(PDO::FETCH_NUM);
    foreach ($rows as $row) {
        echo "ID: " . $row[0] . ", Name: " . $row[1] . "<br>";
    }
} else {
    echo "ไม่พบข้อมูล ใน ตาราง products<br>";
}
?>