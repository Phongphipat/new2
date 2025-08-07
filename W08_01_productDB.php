<?php
require_once 'W07_01_ConnectDB.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loop for Show Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <!-- DataTable CSS -->
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .container {
            max-width: 800px;
        }
    </style>
</head>

<body>
    <?php
    $sql = "SELECT * FROM products";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);



    ?>
    <div class="container mt-5">
        <h1>Product List</h1>
        <form action="" method="POST" class="mb-3">
            <div>
                <input type="number" name="price" placeholder="Enter Price" class="form-control mb2">
                <button type="submit" class="btn btn-success mt-2">Filter</button>
            </div>

        </form>
        <table id="productTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_POST['price']) && !empty($_POST['price'])) {
                    $filterPrice = $_POST['price'];
                    $filterProducts = array_filter($products, function ($product) use ($filterPrice) {
                        return $product['price'] == $filterPrice;
                    });
                    $filterProducts = array_values($filterProducts);


                } else {
                    $filterProducts = $data;
                }

                foreach ($data as $index => $product) {
                    echo "<tr>";
                    echo "<td>" . ($index + 1) . "</td>";
                    echo "<td>" . ($product['product_id']) . "</td>";
                    echo "<td>" . ($product['product_name']) . "</td>";
                    echo "<td>" . ($product['category']) . "</td>";
                    echo "<td>" . ($product['price']) . "</td>";
                    echo "<td>" . ($product['stock_quantity']) . "</td>";
                    echo "</tr>";
                }
                ?>

            </tbody>
        </table>
    </div>
    <script scr="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

    <script>
        let table = new DataTable('#productTable');
    </script>
    <a href="index.php">Home</a>
</body>

</html>