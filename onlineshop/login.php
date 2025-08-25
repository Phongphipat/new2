<?php
session_start();
require_once 'config.php';
$errors = ''; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameoremail = trim($_POST['username_or_email']);
    $password = trim($_POST['password']);
    
    $sql = "SELECT * FROM users WHERE (username = ? OR email = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$usernameoremail, $usernameoremail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
       if ($user['role'] === 'admin') {
            header("Location: admin/index.php");
        } else {
            header("location: index.php");
        }
        exit();
    } else {
        $errors = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เข้าสู่ระบบ</title>
  <style>
      body {
          margin: 0;
          min-height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
          background: linear-gradient(135deg, #f8fbff, #eef3ff);
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }
      .card {
          width: 100%;
          max-width: 420px;
          background: #fff;
          border-radius: 16px;
          box-shadow: 0 8px 28px rgba(0,0,0,0.08);
          overflow: hidden;
      }
      .card-header {
          background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
          padding: 1.5rem;
          text-align: center;
          color: #fff;
      }
      .card-header h2 {
          margin: 0;
          font-size: 1.5rem;
          font-weight: 700;
      }
      .card-body {
          padding: 1.5rem;
      }
      .form-label {
          font-weight: 500;
          margin-bottom: .3rem;
          display: block;
          color: #333;
      }
      .form-control {
          width: 100%;
          border: 1px solid #ddd;
          border-radius: 8px;
          padding: 10px;
          margin-bottom: 1rem;
          font-size: 0.95rem;
      }
      .form-control:focus {
          border-color: #2575fc;
          outline: none;
          box-shadow: 0 0 0 0.15rem rgba(37,117,252,.2);
      }
      .btn-primary {
          width: 100%;
          border: none;
          border-radius: 8px;
          padding: 12px;
          background: #2575fc;
          color: #fff;
          font-weight: 600;
          font-size: 1rem;
          cursor: pointer;
          transition: 0.3s;
      }
      .btn-primary:hover {
          background: #1a5ed6;
      }
      .text-center {
          text-align: center;
          margin-top: 1rem;
      }
      .text-center a {
          text-decoration: none;
          color: #2575fc;
          font-weight: 500;
      }
      .alert {
          padding: 0.8rem 1rem;
          border-radius: 8px;
          margin-bottom: 1rem;
          font-size: 0.9rem;
      }
      .alert-success { background: #d4edda; color: #155724; }
      .alert-danger { background: #f8d7da; color: #721c24; }
  </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <h2><i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ</h2>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['register']) && $_GET['register'] === 'success'): ?>
        <div class="alert alert-success">สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ</div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errors) ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="username_or_email" class="form-label">ชื่อผู้ใช้หรืออีเมล</label>
            <input type="text" name="username_or_email" id="username_or_email" class="form-control" required>

            <label for="password" class="form-label">รหัสผ่าน</label>
            <input type="password" name="password" id="password" class="form-control" required>
            
            <button type="submit" class="btn-primary">เข้าสู่ระบบ</button>
        </form>

        <div class="text-center">
            ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
