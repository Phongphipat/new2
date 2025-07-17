<html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>php bessic</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>

<body>
    <h1>Welcome PHP Basic</h1>
    <p>This is a simple PHP appication.</p>
    <hr>
    <h1 style="color: red;">Basic PHP Syntax</h1>
    <pre>
        &LT;?php
            echo "Hello, World! ";
        ?&GT;
        </pre>
    <h3> Result</h3>
    <div style="color: blue;">
        <?php
        echo "hello world! <br>";
        print "<span style='color: green;'>Nontawat Simmalaotao</span>";

        ?>
    </div>
    <hr>
    <h1 style="color: red;">php variables</h1>
    <pre>
        &LT;?php
            $greeting = "Hello, World!";
            echo $greeting;
        ?&GT;
        </pre>
    <h3> Result</h3>
    <?php
    $greeting = "Hello, World!";
    echo "<span style='color: blue;'>" . $greeting . "</span>";
    ?>
    <hr>
    <h1 style="color : red;">Integer Variable </h1>
    <pre>
            $age = 25;
            echo $age;
        </pre>
    <h3> Result</h3>
    <?php
    $age = 20;
    echo " <span style='color: blue;'>I am " . $age . " years old</span> ";
    echo " <span style='color: blue;'>I am $age years old</span> ";
    ?>
    <hr>
    <h1>calculate with Variables</h1>


    <?php
    $a = 5;

    $b = 4;
    $c = $a + $b;
    echo "<span style='color: blue;'>The sum of $a and $b is $c</span>";
    ?>
    <hr>
    <h1>คำนวนสามเหลี่ยม</h1>


    <?php
    $base = 5;
    $height = 10;
    $area = 0.5 * $base * $height;
    echo "<span style='color: blue;'> พื้นที่สามเหลี่ยมคือ $area ตารางหน่วย</span>";
    ?>
    <hr>
    <h1>คำนวนจากปีเกิด</h1>


    <?php
    $birthYear = 2004;
    $currentYear = 2025;
    $age = $currentYear - $birthYear;
    echo "<span style='color: blue;'>อายุของคุณ $age ปี</span>";
    ?>
    <hr>
    <h1 style="color: red;">IF-Else</h1>
    <!--เกณฑ์ผ่านการสอบมากกว่า 60 คะแนน-->
    <?php
    $score = 75;
    if ($score > 60) {
        echo "<span style='color: green;'>คะแนนของคุณคือ $score<br></span>";
        echo "<span style='color: green;'>คุณผ่านการสอบ</span>";
    } else {
        echo "<span style='color: red;'>คุณไม่ผ่านการสอบ</span>";
    }
    ?>
    <hr>
    <h1 style="color: red;">Boolean Variable</h1>
    <!--ตรวจสอบว่าเป็นนศใหรือไม่-->
    <?php
    $std = true; // true = เป็นนักศึกษา, false = ไม่เป็นนักศึกษา
    echo "<h3>คุณเป็นนักศึกษาใช่หรือไม่?</h3><br>";
    if (!$std) {
        echo "<span style='color: green;'>ใช่, คุณเป็นนักศึกษา</span>";
    } else {
        echo "<span style='color: red;'>ไม่, คุณไม่เป็นนักศึกษา</span>";
    }
    ?>
    <!--------------------------------------------------->
    <hr>
    <h1 style="color: red;">Loop</h1>

    <h2>Loop For</h2>
    <!--LOOP FOR-->
    <h3>แสดง 5 - 9 พร้อม หาผลบวก ของ ตัวเลข</h3>
    <?php
    $sum = 0;
    for ($i = 5; $i < 9; $i++) {
        $sum = $sum + $i;  //$sum += $i;
        echo "$i+";
    }
    if ($i == 9) {
        $sum = $sum + $i;
        echo "$i ";
    }
    echo "= $sum";
    ?>
    <hr>
    <!--LOOP WhIIE-->
    <h2>while loop</h2>
    <h2>สูตรคูณแม่ 2</h2>
    <?php
    $j = 1; // ค่าเริ่มต้น
    while ($j <= 12) {
        echo "2 x $j = " . (2 * $j) . "<br>"; //แสดงผลสูตรคูณแม่ 2
        $j++;//เพิ่ม ลด ค่า
    }
    ?>
    <hr>
    // แสดงผลสูตรคูณแม่ แบบตาราง
    <h2>while loop</h2>
    <h2>สูตรคูณแม่ 2</h2>

    <table class="table table-bordered table-striped w-auto m-auto t-center">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>สูตรคูณ</th>
                <th>ผลลัพธ์</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 1; $i <= 12; $i++) {
                echo "<tr>";
                echo "<td>$i</td>"; // แสดงลำดับ
                echo "<td>2 x $i</td>";
                echo "<td>" . (2 * $i) . "</td>";
                echo "</tr>";
            }
            ?>

        </tbody>
    </table>
    <!-- ======================================================== -->
    <!-- ======================================================== -->
    <!-- ======================================================== -->
    <!-- ======================================================== -->

    <hr>
    <h2>สร้างตัวแปรอาเรย์ แบบที่ 1: Indexed Array</h2>
    <h5>PHP จะกำหนด index เป็นตัวเลขอัตโนมัติ โดยเริ่มจาก 0</h5>
    <hr>

    <?php
    $fuit = ["apple", "Banana", "cherry"];

    ?>
    <h3>แสดงรายการผลไม้ โดยใช้ index</h3>
    <div style="color:blue; background-color: lightgray; padding: 10px;">
        <?php
        echo $fuit[0] . "<br>";
        echo $fuit[1] . "<br>";
        echo $fuit[2] . "<br>";
        ?>
    </div>
    <br>
    <div style="color:red; background-color: lightgray; padding: 10px;">
        <?php
        echo "รายการผลไม้:" . "<br>";
        echo "ผลไม้ที่1:" . $fuit[0] . "<br>";
        echo "ผลไม้ที่3:" . $fuit[2] . "<br>";
        ?>
    </div>
    <h4>แสดงรายการผลไม้ โดยใช้ print readable</h4>
    <div style="color:blue; background-color: lightgray; padding: 10px;">
        <?php
        echo "รายการผลไม้: <br>";
        print_r($fuit); // แสดงผลอาเรย์ทั้งหมด  print readable
        echo "<br>";

        ?>
    </div>
    <h4>แสดงจำนวนสมาชิกในอาเรย์</h4>
    <div style="color:blue; background-color: lightgray; padding: 10px;">
        <?php
        echo "จำนวนผลไม้:" . count($fuit) . "ชนิด" . "<br>";
        echo "<br>";

        ?>
    </div>
    <!-- ======================================================== -->
    <br>
    <h4>แสดงรายการผลไม้ โดยใช้ implode เพื่อแปลงอาเรย์เป็นสตริง</h4>
    <div style="color:blue; background-color: lightgray; padding: 10px;">
        <?php
        // แสดงรายการผลไม้และจำนวนสมาชิกในอาเรย์
        // ใช้ implode เพื่อแปลงอาเรย์เป็นสตริง และแสดงผลลัพธ์
        echo "รายการผลไม้: " . implode(", ", $fuit) . "<br>"; // ผลลัพธ์: Apple, Banana, Cherry
        echo "<br>";
        ?>
    </div>
    <!-- ======================================================== -->
    <br>
    <h4>แสดงรายการผลไม้ ใช้คำสั่ง foreach เพื่อวนลูป</h4>
    <div style="color:blue; background-color: lightgray; padding: 10px;">
        <?php
        // ใช้คำสั่ง foreach เพื่อวนลูปค่าใน array ทีละตัว โดยในแต่ละรอบ ตัวแปร $fruit จะเก็บค่าผลไม้ 1 ชนิด
        foreach ($fuit as $fruit) {
            echo "ผลไม้: $fruit <br>";
        }
        echo "<br>";
        ?>

        <?php
        // ใช้คำสั่ง foreach เพื่อวนลูปค่าใน array ทีละตัว โดยในแต่ละรอบ ตัวแปร $fruit จะเก็บค่าผลไม้ 1 ชนิด
        echo "ผลไม้: ";
        foreach ($fuit as $fruit) {
            if ($fruit == end($fuit)) {
                echo "$fruit.";
            } else {
                echo "$fruit,";
            }
        }
        echo "<br>";

        ?>
    </div>

    <!-- ======================================================== -->
    <!-- ======================================================== -->

    <hr>
    <h2>สร้างตัวแปรอาเรย์ แบบที่ 2: Associative Array</h2>
    <h6>เป็น array ซ้อนกันหลายชุด (Multidimensional array)</h6>
    <h6>แต่ละชุดเป็น associative array ที่ระบุชื่อ key ชัดเจน เช่น "name" และ "price"</h6>
    <h6>ใช้สำหรับเก็บข้อมูลที่มีความสัมพันธ์กัน key => value เช่น รายการสินค้า</h6>


    <?php
    // สร้างอาเรย์ของผลไม้ที่มีชื่อและราคา
    $products = [
        ["name" => "Apple", "price" => 30],
        ["name" => "Banana", "price" => 20],
        ["name" => "Cherry", "price" => 15]
    ];
    ?>
    <!-- ======================================================== -->
    <br>
    <h4>แสดงรายการผลไม้ ใช้คำสั่ง key value</h4>
    <div style="color:blue; background-color: lightgray; padding: 10px;">
        <?php
        // แสดงผลลัพธ์ของการเข้าถึงข้อมูลในอาเรย์
        echo $products[0]["name"] . "<br>";  // Apple
        echo $products[2]["price"] . "<br>"; // 15
        

        ?>
    </div>
    <!-- ======================================================== -->
    <br>
    <h4>แสดงรายการผลไม้ ใช้คำสั่ง foreach เพื่อวนลูป</h4>
    <div style="color:red; background-color: lightgray; padding: 10px;">
        <?php
        $total_price = 0;
        foreach ($products as $product) {
            echo "รายการสินค้า: $product[name] ";
            echo "ราคา: $product[price] บาท" . " <br>";
            $total_price += $product["price"];

        }
        echo "<br>";
        echo "ราคารวม:" . $total_price;

        ?>
    </div>
    <br>


    <br><br>


    <a href="index.php">กลับสู่หน้าแรก</a>


</body>

</html>