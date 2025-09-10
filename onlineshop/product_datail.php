<?php
session_start();
require_once 'config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่ง id สินค้าเข้ามาหรือไม่
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$product_id = $_GET['id'];

// ดึงข้อมูลสินค้า
$stmt = $conn->prepare("
    SELECT p.*, c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.category_id
    WHERE p.product_id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// ถ้าไม่พบสินค้า
if (!$product) {
    echo "<h3 class='text-danger text-center mt-5'>❌ ไม่พบสินค้าที่คุณต้องการ</h3>";
    exit;
}

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายละเอียดสินค้า</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Kanit', sans-serif;
      background: #f8f9fa;
    }

    .product-card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      max-width: 800px;
      margin: auto;
      padding: 20px;
    }

    .product-title {
      font-weight: 700;
      font-size: 1.8rem;
      color: #333;
    }

    .product-price {
      font-size: 1.4rem;
      font-weight: 600;
      color: #b22222;
    }

    .btn-success {
      border-radius: 50px;
      padding: 10px 20px;
      font-weight: 600;
    }

    .btn-secondary {
      border-radius: 50px;
    }

    .stock {
      font-weight: 500;
      color: #555;
    }

    .back-btn {
      border-radius: 50px;
      padding: 8px 16px;
      font-weight: 500;
    }
  </style>
</head>
<body class="container mt-4">

  <a href="index.php" class="btn btn-secondary back-btn mb-3">← กลับหน้ารายการสินค้า</a>

  <div class="card product-card">
    <div class="card-body">
      <h3 class="product-title"><?= htmlspecialchars($product['product_name']) ?></h3>
      <h6 class="text-muted mb-3">หมวดหมู่: <?= htmlspecialchars($product['category_name']) ?></h6>

      <p class="card-text"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

      <p class="product-price mt-3">ราคา: <?= number_format($product['price'], 2) ?> บาท</p>
      <p class="stock">คงเหลือ: <?= (int)$product['stock'] ?> ชิ้น</p>

      <?php if ($isLoggedIn): ?>
        <form action="cart.php" method="post" class="mt-3">
          <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

          <label for="quantity" class="form-label">จำนวน:</label>
          <input type="number" name="quantity" id="quantity"
                 value="1" min="1" max="<?= (int)$product['stock'] ?>"
                 class="form-control mb-3" style="max-width: 120px;" required>

          <button type="submit" class="btn btn-success">🛒 เพิ่มในตะกร้า</button>
        </form>
      <?php else: ?>
        <div class="alert alert-info mt-3">ℹ️ กรุณาเข้าสู่ระบบเพื่อสั่งซื้อสินค้า</div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
