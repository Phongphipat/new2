<?php
require '../config.php';
require 'auth_admin.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND role = 'member'");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<h3 class='text-center text-danger mt-5'>ไม่พบสมาชิก</h3>";
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($username === '' || $email === '') {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "รูปแบบอีเมลไม่ถูกต้อง";
    }

    if (!$error) {
        $chk = $conn->prepare("SELECT 1 FROM users WHERE (username = ? OR email = ?) AND user_id != ?");
        $chk->execute([$username, $email, $user_id]);
        if ($chk->fetch()) {
            $error = "ชื่อผู้ใช้หรืออีเมลนี้มีอยู่ในระบบแล้ว";
        }
    }

    $updatePassword = false;
    $hashed = null;
    if (!$error && ($password !== '' || $confirm !== '')) {
        if (strlen($password) < 6) {
            $error = "รหัสผ่านต้องยาวอย่างน้อย 6 ตัวอักษร";
        } elseif ($password !== $confirm) {
            $error = "รหัสผ่านใหม่กับยืนยันรหัสผ่านไม่ตรงกัน";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $updatePassword = true;
        }
    }

    if (!$error) {
        if ($updatePassword) {
            $sql = "UPDATE users SET username=?, full_name=?, email=?, password=? WHERE user_id=?";
            $args = [$username, $full_name, $email, $hashed, $user_id];
        } else {
            $sql = "UPDATE users SET username=?, full_name=?, email=? WHERE user_id=?";
            $args = [$username, $full_name, $email, $user_id];
        }
        $upd = $conn->prepare($sql);
        $upd->execute($args);

        header("Location: users.php");
        exit;
    }

    $user['username'] = $username;
    $user['full_name'] = $full_name;
    $user['email'] = $email;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไขข้อมูลสมาชิก</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: #f5f7fa;
    }
    .card {
        border-radius: 15px;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
    }
    .form-label {
        font-weight: 600;
    }
</style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-lg-7 col-md-9">
        <div class="card p-4">
            <h2 class="text-center mb-3 text-primary">✏️ แก้ไขข้อมูลสมาชิก</h2>
            <a href="users.php" class="btn btn-sm btn-outline-secondary mb-3">← กลับหน้ารายชื่อสมาชิก</a>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ชื่อผู้ใช้</label>
                    <input type="text" name="username" class="form-control" required 
                        value="<?= htmlspecialchars($user['username']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">ชื่อ - นามสกุล</label>
                    <input type="text" name="full_name" class="form-control" 
                        value="<?= htmlspecialchars($user['full_name']) ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control" required 
                        value="<?= htmlspecialchars($user['email']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">รหัสผ่านใหม่ <small class="text-muted">(ถ้าไม่เปลี่ยนให้เว้นว่าง)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" name="confirm_password" class="form-control">
                </div>

                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5">💾 บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
