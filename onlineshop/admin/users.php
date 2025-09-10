<?php
session_start();
require '../config.php'; // เชื่อมต่อฐานข้อมูล
require_once 'auth_admin.php';

// ลบสมาชิก
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    // ป้องกันลบตัวเอง
    if ($user_id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND role = 'member'");
        $stmt->execute([$user_id]);
    }
    header("Location: users.php");
    exit;
}

// ดึงข้อมูลสมาชิก
$stmt = $conn->prepare("SELECT * FROM users WHERE role = 'member' ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>จัดการสมาชิก</title>
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
      border-left: 6px solid #198754;
      padding-left: 12px;
    }

    .table {
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .table th {
      background: #198754;
      color: #fff;
      font-weight: 600;
    }

    .table td {
      vertical-align: middle;
    }

    .btn-sm {
      border-radius: 50px;
      padding: 5px 12px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .alert {
      border-radius: 10px;
      font-weight: 500;
    }

    .back-btn {
      border-radius: 50px;
      font-weight: 500;
      margin-bottom: 20px;
    }
  </style>
</head>

<body class="container mt-4">
  <h2>จัดการสมาชิก</h2>
  <a href="index.php" class="btn btn-secondary back-btn">← กลับหน้าผู้ดูแล</a>

  <?php if (count($users) === 0): ?>
    <div class="alert alert-warning">ยังไม่มีสมาชิกในระบบ</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th>ชื่อผู้ใช้</th>
            <th>ชื่อ-นามสกุล</th>
            <th>อีเมล</th>
            <th>วันที่สมัคร</th>
            <th class="text-center">จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user['username']) ?></td>
              <td><?= htmlspecialchars($user['full_name']) ?></td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td><?= $user['created_at'] ?></td>
              <td class="text-center">
                <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                <a href="users.php?delete=<?= $user['user_id'] ?>" class="btn btn-sm btn-danger"
                   onclick="return confirm('คุณต้องการลบสมาชิกนี้หรือไม่?')">ลบ</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</body>
</html>
