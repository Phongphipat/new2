<?php
// categories.php
require '../config.php';
require 'auth_admin.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Admin guard ---
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// --- PDO error mode ---
if ($conn instanceof PDO) {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

// --- CSRF token ---
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

// --- Actions (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
            throw new Exception('CSRF token ไม่ถูกต้อง');
        }

        if (isset($_POST['add_category'])) {
            $category_name = trim($_POST['category_name'] ?? '');
            if ($category_name === '') {
                throw new Exception('กรุณากรอกชื่อหมวดหมู่');
            }
            $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (:name)");
            $stmt->execute([':name' => $category_name]);
            $_SESSION['success'] = 'เพิ่มหมวดหมู่เรียบร้อย';
        }

        if (isset($_POST['update_category'])) {
            $category_id = (int) ($_POST['category_id'] ?? 0);
            $new_name = trim($_POST['new_name'] ?? '');
            if ($category_id <= 0 || $new_name === '') {
                throw new Exception('ข้อมูลไม่ครบถ้วนสำหรับอัปเดตหมวดหมู่');
            }
            $stmt = $conn->prepare("UPDATE categories SET category_name = :name WHERE category_id = :id");
            $stmt->execute([':name' => $new_name, ':id' => $category_id]);
            $_SESSION['success'] = 'อัปเดตหมวดหมู่เรียบร้อย';
        }

        if (isset($_POST['delete_category'])) {
            $category_id = (int) ($_POST['category_id'] ?? 0);
            if ($category_id <= 0) {
                throw new Exception('ไม่พบรหัสหมวดหมู่ที่ต้องการลบ');
            }

            $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
            $stmt->execute([':id' => $category_id]);
            $inUse = (int) $stmt->fetchColumn();

            if ($inUse > 0) {
                $_SESSION['error'] = "ไม่สามารถลบได้: ยังมีสินค้าอยู่ในหมวดนี้จำนวน {$inUse} รายการ";
            } else {
                $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = :id");
                $stmt->execute([':id' => $category_id]);
                $_SESSION['success'] = 'ลบหมวดหมู่เรียบร้อย';
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    header("Location: categories.php");
    exit;
}

// --- ดึงหมวดหมู่ทั้งหมด ---
$stmt = $conn->query("SELECT category_id, category_name AS name FROM categories ORDER BY category_id ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการหมวดหมู่สินค้า</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fa; }
        .card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .table th { background: #0d6efd; color: #fff; text-align: center; }
        .table td { vertical-align: middle; }
    </style>
</head>
<body class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary">📂 จัดการหมวดหมู่สินค้า</h2>
        <a href="index.php" class="btn btn-outline-secondary">← กลับหน้าผู้ดูแล</a>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- เพิ่มหมวดหมู่ -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white fw-bold">➕ เพิ่มหมวดหมู่ใหม่</div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                <div class="col-md-8">
                    <input type="text" name="category_name" class="form-control" placeholder="ชื่อหมวดหมู่ใหม่" required>
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" name="add_category" class="btn btn-success px-4">💾 บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    <!-- รายการหมวดหมู่ -->
    <div class="card">
        <div class="card-header bg-dark text-white fw-bold">📋 รายการหมวดหมู่</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center">
                    <thead>
                        <tr>
                            <th style="width:50%">ชื่อหมวดหมู่</th>
                            <th style="width:30%">แก้ไขชื่อ</th>
                            <th style="width:20%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="3" class="text-muted">ยังไม่มีหมวดหมู่</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="text-start"><?= htmlspecialchars($cat['name']) ?></td>
                                    <td>
                                        <form method="post" class="d-flex gap-2 justify-content-center">
                                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                            <input type="hidden" name="category_id" value="<?= (int) $cat['category_id'] ?>">
                                            <input type="text" name="new_name" class="form-control"
                                                value="<?= htmlspecialchars($cat['name']) ?>" required>
                                            <button type="submit" name="update_category" class="btn btn-warning btn-sm">✏️</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="post" onsubmit="return confirm('ต้องการลบหมวดหมู่นี้หรือไม่?');" class="d-inline">
                                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                            <input type="hidden" name="category_id" value="<?= (int) $cat['category_id'] ?>">
                                            <button type="submit" name="delete_category" class="btn btn-danger btn-sm">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
