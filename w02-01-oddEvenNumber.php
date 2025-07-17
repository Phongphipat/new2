<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>odd-even</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
    <div class = "container mt-5 text-center">
        <h1 class="text-center">Odd and Even Numbers Checker</h1>
        <hr>
        <p class="text-center">กรุณากรอกตัวเลขเพื่อตรวจสอบ</p>
        <form action="" method="post">
            <div class = "form-group">
                <input type="number" name = "number" class = "form-control w-50 mx-auto" placeholder="กรุณากรอกตัวเลข" required id="number">
            </div> 
            <button type = "submit" class = "btn btn-primary mt-3">ตรวจสอบ</button>
        </form>

    <div>
        <!--แสดงผลลัพท์-->
       
        <?php
         $get_number = $_POST["number"] ?? null;
         if ($get_number != 0) {
                if ($get_number % 2 == 0) {
                    echo "<h3 class='text-success text-center'>$get_number is even</h3>";
                } else {
                    echo "<h3 class='text-danger text-center'>$get_number is odd</h3>";
                }
            }
        ?>
    </div>
    <a href="index.php">กลับสู่หน้าแรก</a>
</body>
</html>