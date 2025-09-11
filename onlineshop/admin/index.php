<?php
session_start();
require_once '../config.php';
// ตรวจสอบสิทธิ์ admin
require_once 'auth_admin.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>แผงควบคุมผู้ดูแลระบบ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Kanit', sans-serif;
      background: #f4f6f9;
      color: #333;
    }

    h2 {
      font-weight: 700;
      margin-bottom: 20px;
      border-left: 6px solid #007bff;
      padding-left: 12px;
    }

    .admin-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      text-align: center;
      padding: 25px 15px;
    }

    .admin-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .admin-card .btn {
      font-weight: 600;
      font-size: 1rem;
      padding: 12px;
      border-radius: 50px;
      width: 100%;
    }

    .welcome {
      font-size: 1.1rem;
      color: #555;
      margin-bottom: 30px;
    }

    .logout-btn {
      margin-top: 25px;
      font-weight: 500;
      border-radius: 50px;
      padding: 10px 25px;
    }
  </style>
</head>

<body class="container mt-4">
  <h2>ระบบผู้ดูแลระบบ</h2>
  <p class="welcome">ยินดีต้อนรับ, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>

  <div class="row g-4">
    <div class="col-md-3 col-sm-6">
      <div class="admin-card">
        <a href="products.php" class="btn btn-primary">จัดการสินค้า</a>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="admin-card">
        <a href="orders.php" class="btn btn-success">จัดการคำสั่งซื้อ</a>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="admin-card">
        <a href="users.php" class="btn btn-warning">จัดการสมาชิก</a>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="admin-card">
        <a href="categories.php" class="btn btn-dark">จัดการหมวดหมู่</a>
      </div>
    </div>
  </div>

  <a href="../logout.php" class="btn btn-secondary logout-btn">ออกจากระบบ</a>
</body>

</html>