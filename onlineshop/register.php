<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่า form data
    $username = trim($_POST['username']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    $hashedpassword = password_hash($password, algo: PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, full_name, email, password, role) VALUES (?, ?, ?, ?, 'admin')";
    $stmt = $conn->prepare($sql);
    $stmt->execute(params: [$username, $name, $email, $hashedpassword]);



}
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>


<body>
    <div class="container mt-5">
        <h1>สมัครสมาชิก</h1>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="username" name="username" required placeholder="ชื่อผู้ใช้">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อ-สกุล</label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="ชื่อ-สกุล">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="อีเมล">
            </div>
            <div class="mb-3">

                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" required
                    placeholder="รหัสผ่าน">
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">ยืนยันรหัสผ่าน</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                    placeholder="ยืนยันรหัสผ่าน">
            </div>

            <button type="submit" class="btn btn-primary">สมัครสมาชิก</button>
            <a href="login.php" class="btn-link">เข้าสู่ระบบ</a>


        </form>
    </div>
</body>

</html>
<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'online_shop';

$dns = "mysql:host=$host;dbname=$database";

try {
    //  $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn = new PDO($dns, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "PDO Connected successfully";
} catch (PDOException $e) {
    echo "PDO Connection failed: " . $e->getMessage();
}
?>

<script scr="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>