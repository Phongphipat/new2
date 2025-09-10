<?php
session_start(); // เริ่ม session
require_once 'config.php'; // เชื่อมต่อฐานข้อมูล

$isLoggedIn = isset($_SESSION['user_id']); // ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือไม่

// ดึงข้อมูลสินค้า
$stmt = $conn->query("SELECT p.*, c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    ORDER BY p.created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>หน้าหลัก</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Kanit', sans-serif;
      background: #f8f9fa;
    }

    h1 {
      font-weight: 700;
      color: #b22222;
      border-left: 6px solid #b22222;
      padding-left: 12px;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #333;
    }

    .card-subtitle {
      font-size: 0.9rem;
      color: #777;
    }

    .card-text {
      font-size: 0.95rem;
      color: #555;
      min-height: 60px;
    }

    .btn-sm {
      border-radius: 50px;
      font-weight: 500;
    }

    .navbar-actions a {
      margin-left: 5px;
    }

    @media (max-width: 768px) {
      h1 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>

<body class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>รายการสินค้า</h1>

    <div class="navbar-actions">
      <?php if ($isLoggedIn): ?>
        <span class="me-3">ยินดีต้อนรับ, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> (<?= $_SESSION['role'] ?>)</span>
        <a href="profile.php" class="btn btn-info">ข้อมูลส่วนตัว</a>
        <a href="cart.php" class="btn btn-warning">ดูตะกร้าสินค้า</a>
        <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-success">เข้าสู่ระบบ</a>
        <a href="register.php" class="btn btn-primary">สมัครสมาชิก</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- รายการสินค้า -->
  <div class="row">
    <?php foreach ($products as $product): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
            <h6 class="card-subtitle mb-2"><?= htmlspecialchars($product['category_name']) ?></h6>
            <p class="card-text"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <p><strong>ราคา:</strong> <?= number_format($product['price'], 2) ?> บาท</p>

            <?php if ($isLoggedIn): ?>
              <form action="cart.php" method="post" class="d-inline">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-sm btn-success">เพิ่มในตะกร้า</button>
              </form>
            <?php else: ?>
              <small class="text-muted">เข้าสู่ระบบเพื่อสั่งสินค้า</small>
            <?php endif; ?>

            <!-- แก้ให้เชื่อมไปที่ product_datail.php -->
            <a href="product_datail.php?id=<?= $product['product_id'] ?>"
               class="btn btn-sm btn-outline-primary float-end">ดูรายละเอียด</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
