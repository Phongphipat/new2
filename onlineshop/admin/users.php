<?php
// users.php (แก้ไขให้ใช้ POST + CSRF + SweetAlert2)
if (session_status() === PHP_SESSION_NONE)
  session_start();

require '../config.php';        // เชื่อมต่อฐานข้อมูล
require_once 'auth_admin.php';  // ตรวจสอบสิทธิ์ (สมมติไฟล์นี้ใช้ session)

# สร้าง CSRF token ถ้ายังไม่มี
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* ======= HANDLE DELETE (POST) ======= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['u_id'])) {
  // ตรวจสอบ CSRF token
  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    // โยนกลับหรือแสดง error ตามต้องการ
    header('Location: users.php');
    exit;
  }

  $user_id = (int) $_POST['u_id'];

  // ห้ามลบตัวเอง
  if ($user_id !== (int) ($_SESSION['user_id'] ?? 0)) {
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND role = 'member'");
    $stmt->execute([$user_id]);
  }

  header('Location: users.php');
  exit;
}

/* ======= ดึงข้อมูลสมาชิก ======= */
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- เอา CSRF token ไว้ใน meta เพื่อให้ JS อ่านได้ -->
  <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

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
              <td>
                <?= htmlspecialchars(
                  (strtotime($user['created_at']) ? date('Y-m-d H:i', strtotime($user['created_at'])) : $user['created_at'])
                ) ?>
              </td>
              <td class="text-center">
                <a href="edit_user.php?id=<?= (int) $user['user_id'] ?>" class="btn btn-sm btn-warning">แก้ไข</a>

                <!-- ปุ่มลบ เปลี่ยนเป็น button มี class และ data-user-id ให้ JS จับ -->
                <button type="button" class="btn btn-sm btn-danger delete-button"
                  data-user-id="<?= (int) $user['user_id'] ?>">
                  ลบ
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <script>
    // อ่าน token จาก meta
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function showDeleteConfirmation(userId) {
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: 'การลบจะไม่สามารถกู้คืนได้!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก',
      }).then((result) => {
        if (result.isConfirmed) {
          // สร้าง form แบบ POST ส่ง u_id และ csrf_token
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = ''; // ส่งกลับมาที่ users.php
          form.style.display = 'none';

          const inputId = document.createElement('input');
          inputId.type = 'hidden';
          inputId.name = 'u_id';
          inputId.value = userId;
          form.appendChild(inputId);

          const inputToken = document.createElement('input');
          inputToken.type = 'hidden';
          inputToken.name = 'csrf_token';
          inputToken.value = csrfToken;
          form.appendChild(inputToken);

          document.body.appendChild(form);
          form.submit();
        }
      });
    }

    // ผูก event ให้ปุ่มลบ (เรียกเมื่อ DOM โหลดเสร็จ)
    document.addEventListener('DOMContentLoaded', function () {
      const deleteButtons = document.querySelectorAll('.delete-button');
      deleteButtons.forEach((button) => {
        button.addEventListener('click', function () {
          const userId = this.getAttribute('data-user-id');
          showDeleteConfirmation(userId);
        });
      });
    });
  </script>

</body>

</html>