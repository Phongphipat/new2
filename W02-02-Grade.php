<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
    <div class = "container mt-5 text-center">
        <h1 class="text-center">PHP Check Grade A-E from Score</h1>
        <hr>
        <p class="text-center">กรุณากรอกคะแนน</p>
        <form action="" method="post">
            <div class = "form-group">
                <input type="number" name = "score" value="<?php echo isset($_POST['score']) ? $_POST['score'] : ''; ?>" class = "form-control w-50 mx-auto" placeholder="กรุณากรอกคะแนน" required id="score">
            </div> 
            <button type = "submit" class = "btn btn-primary mt-3">ตรวจสอบ</button>
            <button type = "button" class = "btn btn-secondary mt-3" onclick="clearGrade()">รีเซ็ตข้อมูล</button>
        </form>

    
        <!--แสดงผลลัพท์-->
        <div id = "grade" class="mt-3">
       
        <?php
         $get_score = $_POST["score"] ?? null;
         
            if ($get_score !== null) {
            if ($get_score >= 80) {
                echo "<h3 class='text-success text-center'>$get_score คะแนน เกรดของคุณ คือ A</h3>";
            } elseif ($get_score >= 70) {
                echo "<h3 class='text-success text-center'>$get_score คะแนน เกรดของคุณ คือ B</h3>";
            } elseif ($get_score >= 60) {
                echo "<h3 class='text-success text-center'>$get_score คะแนน เกรดของคุณ คือ C</h3>";
            } elseif ($get_score >= 50) {
                echo "<h3 class='text-success text-center'>$get_score คะแนน เกรดของคุณ คือ D</h3>";
            } else {
                echo "<h3 class='text-danger text-center'>$get_score คะแนน เกรดของคุณ คือ E</h3>";
            }
        }
        else {
            echo "<h3 class='text-warning text-center'>กรุณากรอกคะแนน</h3>";
        }
           
        ?>
        </div>
    
    <a href="index.php">กลับสู่หน้าแรก</a>
    <script>
        // ฟังก์ชันสำหรับล้างผลลัพธ์เกรดและช่องกรอกคะแนน
        function clearGrade() {
            document.getElementById('grade').innerHTML = '';
            document.getElementById('score').value = '';
        }  
    </script>
</body>
</html>