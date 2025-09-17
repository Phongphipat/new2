<?php
require_once '../config.php';
require_once 'auth_admin.php';

// การ์ดสิทธิ์ (Admin Guard)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// เพิ่มสินค้าใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);

    if (!empty($name) && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, stock, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $stock, $category_id]);

        header("Location: products.php");
        exit;
    }
}

// ลบสินค้า
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);

    header("Location: products.php");
    exit;
}

// ดึงรายการสินค้า
$stmt = $conn->query("SELECT p.*, c.category_name 
                      FROM products p 
                      LEFT JOIN categories c ON p.category_id = c.category_id 
                      ORDER BY p.created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงหมวดหมู่
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .table th {
            background: #0d6efd;
            color: #fff;
            text-align: center;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary">🛒 จัดการสินค้า</h2>
        <a href="index.php" class="btn btn-outline-secondary">← กลับหน้าผู้ดูแล</a>
    </div>

    <!-- ฟอร์ม เพิ่มสินค้าใหม่ -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            ➕ เพิ่มสินค้าใหม่
        </div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">ชื่อสินค้า</label>
                    <input type="text" name="product_name" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">ราคา (บาท)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">จำนวน</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">หมวดหมู่</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- เลือกหมวดหมู่ --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">รายละเอียดสินค้า</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" name="add_product" class="btn btn-success px-4">💾 บันทึกสินค้า</button>
                </div>
            </form>
        </div>
    </div>

    <!-- แสดงรายการสินค้า -->
    <div class="card">
        <div class="card-header bg-dark text-white fw-bold">
            📋 รายการสินค้า
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 text-center">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>หมวดหมู่</th>
                        <th>ราคา</th>
                        <th>คงเหลือ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products): ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td class="text-start"><?= htmlspecialchars($p['product_name']) ?></td>
                                <td><span class="badge bg-info"><?= htmlspecialchars($p['category_name']) ?></span></td>
                                <td class="text-success fw-bold"><?= number_format($p['price'], 2) ?> ฿</td>
                                <td>
                                    <?php if ($p['stock'] > 10): ?>
                                        <span class="badge bg-success"><?= $p['stock'] ?></span>
                                    <?php elseif ($p['stock'] > 0): ?>
                                        <span class="badge bg-warning text-dark"><?= $p['stock'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">หมด</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit_product.php?id=<?= $p['product_id'] ?>" class="btn btn-sm btn-warning">✏️ แก้ไข</a>
                                    <a href="products.php?delete=<?= $p['product_id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('ยืนยันการลบสินค้านี้?')">🗑️ ลบ</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-muted">ไม่มีสินค้าในระบบ</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
