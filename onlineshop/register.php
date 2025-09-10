<?php
/******************** DB CONNECT (บนสุด) ********************/
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'online_shop';
$dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";

try {
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}

/******************** HANDLE FORM ********************/
$message = null;
$variant = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_in = trim($_POST['username'] ?? '');
    $name_in     = trim($_POST['name'] ?? '');
    $email_in    = trim($_POST['email'] ?? '');
    $pw_in       = trim($_POST['password'] ?? '');
    $cpw_in      = trim($_POST['confirm_password'] ?? '');

    // ตรวจสอบฝั่งเซิร์ฟเวอร์
    if ($pw_in !== $cpw_in) {
        $message = 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน';
        $variant = 'danger';
    } elseif (!filter_var($email_in, FILTER_VALIDATE_EMAIL)) {
        $message = 'อีเมลไม่ถูกต้อง';
        $variant = 'danger';
    } elseif ($username_in === '' || $name_in === '' || $pw_in === '') {
        $message = 'กรุณากรอกข้อมูลให้ครบถ้วน';
        $variant = 'danger';
    } else {
        try {
            // กันซ้ำอีเมล/ชื่อผู้ใช้
            $chk = $conn->prepare("SELECT 1 FROM users WHERE email = ? OR username = ? LIMIT 1");
            $chk->execute([$email_in, $username_in]);
            if ($chk->fetch()) {
                $message = 'อีเมลหรือชื่อผู้ใช้ถูกใช้งานแล้ว';
                $variant = 'warning';
            } else {
                $hashed = password_hash($pw_in, PASSWORD_DEFAULT);
                // หมายเหตุ: คง role = 'admin' ตามโค้ดเดิมของคุณ
                $sql = "INSERT INTO users (username, full_name, email, password, role) VALUES (?, ?, ?, ?, 'member')";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$username_in, $name_in, $email_in, $hashed]);

                $message = 'สมัครสมาชิกสำเร็จ! คุณสามารถเข้าสู่ระบบได้แล้ว';
                $variant = 'success';
                // ตัวอย่าง redirect หลังบันทึก (ถ้าต้องการ):
                // header('Location: login.php?registered=1'); exit;
            }
        } catch (PDOException $e) {
            $message = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
            $variant = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>สมัครสมาชิก</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root { --card-width: 420px; }
        body{
            font-family:"Prompt",system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif;
            min-height:100vh;
            background:linear-gradient(135deg,#e3f2fd 0%,#f5f7ff 50%,#f0fff3 100%);
            display:grid; place-items:center; padding:24px;
        }
        .auth-card{
            width:min(100%,var(--card-width));
            border:none; border-radius:1.25rem;
            box-shadow:0 20px 50px rgba(38,57,77,.25); overflow:hidden;
        }
        .auth-card .card-header{
            background:linear-gradient(135deg,#0d6efd,#6f42c1);
            color:#fff; padding:28px 24px;
        }
        .brand-badge{
            display:inline-flex; align-items:center; gap:10px; padding:8px 12px;
            background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
            border-radius:999px; backdrop-filter:blur(6px); font-weight:500;
        }
        .card-body{ padding:28px 24px 22px; }
        .form-floating>label{ color:#6c757d; }
        .form-control{ border-radius:.75rem!important; }
        .input-group .btn{ border-radius:.75rem!important; }
        .divider{
            display:flex; align-items:center; gap:14px; color:#6c757d; font-size:.925rem; margin:10px 0 0;
        }
        .divider::before,.divider::after{ content:""; height:1px; flex:1; background:#e9ecef; }
        .card-footer{ background:transparent; padding:16px 24px 24px; }
        .btn-primary{ border-radius:.9rem; padding:12px 16px; font-weight:600; }
        .muted-link{ color:#6c757d; text-decoration:none; }
        .muted-link:hover{ color:#0d6efd; text-decoration:underline; }
    </style>
</head>
<body>

<div class="card auth-card">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h1 class="h4 mb-1">สมัครสมาชิก</h1>
                <div class="small opacity-75">สร้างบัญชีใหม่เพื่อเริ่มต้นใช้งาน</div>
            </div>
            <span class="brand-badge">
                <i class="bi bi-bag-check-fill"></i>
                <span>Online Shop</span>
            </span>
        </div>
    </div>

    <div class="card-body">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo htmlspecialchars($variant); ?> d-flex align-items-center" role="alert">
                <i class="bi <?php echo $variant==='success'?'bi-check-circle-fill':($variant==='warning'?'bi-exclamation-circle-fill':'bi-exclamation-triangle-fill'); ?> me-2"></i>
                <div><?php echo htmlspecialchars($message); ?></div>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="needs-validation" novalidate>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="username" required>
                <label for="username"><i class="bi bi-person me-1"></i> ชื่อผู้ใช้</label>
                <div class="invalid-feedback">กรุณากรอกชื่อผู้ใช้</div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="fullname" required>
                <label for="name"><i class="bi bi-card-text me-1"></i> ชื่อ-สกุล</label>
                <div class="invalid-feedback">กรุณากรอกชื่อ-สกุล</div>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email"><i class="bi bi-envelope me-1"></i> อีเมล</label>
                <div class="invalid-feedback">กรุณากรอกอีเมลให้ถูกต้อง</div>
            </div>

            <div class="mb-3">
                <div class="input-group">
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
                        <label for="password"><i class="bi bi-shield-lock me-1"></i> รหัสผ่าน</label>
                        <div class="invalid-feedback">กรุณากรอกรหัสผ่าน</div>
                    </div>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="สลับการมองเห็นรหัสผ่าน">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <div class="input-group">
                    <div class="form-floating flex-grow-1">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="confirm" required>
                        <label for="confirm_password"><i class="bi bi-shield-check me-1"></i> ยืนยันรหัสผ่าน</label>
                        <div class="invalid-feedback">กรุณายืนยันรหัสผ่าน</div>
                    </div>
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirm" aria-label="สลับการมองเห็นยืนยันรหัสผ่าน">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="form-text" id="confirmHelp"></div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-person-plus me-1"></i> สมัครสมาชิก
            </button>

            <div class="divider mt-3 mb-1">หรือ</div>
            <div class="text-center mt-2">
                <a href="login.php" class="muted-link"><i class="bi bi-box-arrow-in-right me-1"></i> เข้าสู่ระบบ</a>
            </div>
        </form>
    </div>

    <div class="card-footer text-center">
        <small class="text-muted">© <?php echo date('Y'); ?> Online Shop • ปลอดภัยด้วยการเข้ารหัสรหัสผ่าน</small>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* Bootstrap validation */
(() => {
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity() || !checkMatch()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

/* Toggle password visibility */
const togglePassword = document.getElementById('togglePassword');
const toggleConfirm  = document.getElementById('toggleConfirm');
const passwordInput  = document.getElementById('password');
const confirmInput   = document.getElementById('confirm_password');

function toggleEye(btn, input) {
    const icon = btn.querySelector('i');
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
}

togglePassword?.addEventListener('click', () => toggleEye(togglePassword, passwordInput));
toggleConfirm?.addEventListener('click',  () => toggleEye(toggleConfirm,  confirmInput));

/* ตรวจสอบยืนยันรหัสผ่าน (ไม่มีตัววัดความแข็งแกร่งแล้ว) */
const confirmHelp = document.getElementById('confirmHelp');
function checkMatch() {
    const match = confirmInput.value && (confirmInput.value === passwordInput.value);
    confirmHelp.textContent = match ? 'รหัสผ่านตรงกัน' : (confirmInput.value ? 'รหัสผ่านไม่ตรงกัน' : '');
    confirmHelp.className = 'form-text ' + (match ? 'text-success' : 'text-danger');
    return !!match;
}
confirmInput?.addEventListener('input', checkMatch);
passwordInput?.addEventListener('input', () => { if (confirmInput.value) checkMatch(); });
</script>

</body>
</html>
