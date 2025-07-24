<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loop for Show Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <!-- DataTable CSS -->
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
        }
    </style>
</head>

<body>
    <?php
    $products = [
        ['id' => 1001, 'name' => 'Apple', 'price' => 60, 'quantity' => 50],
        ['id' => 1002, 'name' => 'Banana', 'price' => 15, 'quantity' => 120],
        ['id' => 1003, 'name' => 'Durian', 'price' => 120, 'quantity' => 15],
        ['id' => 1004, 'name' => 'Mango', 'price' => 45, 'quantity' => 70],
        ['id' => 1005, 'name' => 'Mangosteen', 'price' => 30, 'quantity' => 90],
        ['id' => 1006, 'name' => 'Watermelon', 'price' => 25, 'quantity' => 40],
        ['id' => 1007, 'name' => 'Pineapple', 'price' => 35, 'quantity' => 60],
        ['id' => 1008, 'name' => 'Orange', 'price' => 20, 'quantity' => 150],
        ['id' => 1009, 'name' => 'Grape', 'price' => 80, 'quantity' => 25],
        ['id' => 1010, 'name' => 'Papaya', 'price' => 28, 'quantity' => 55],
        ['id' => 1011, 'name' => 'Lychee', 'price' => 70, 'quantity' => 30],
        ['id' => 1012, 'name' => 'Rambutan', 'price' => 40, 'quantity' => 80],
        ['id' => 1013, 'name' => 'Longan', 'price' => 55, 'quantity' => 45],
        ['id' => 1014, 'name' => 'Guava', 'price' => 22, 'quantity' => 100],
        ['id' => 1015, 'name' => 'Coconut', 'price' => 40, 'quantity' => 35],
    ];
    ?>
    <div class="container mt-5">
        <h1>Product List</h1>
        <form action="" method="get" class="mb-3">
            <div>
                <input type="text" name="price" placeholder="Enter keyword (e.g., 7, Apple, 40)"
                    class="form-control mb-2"
                    value="<?= isset($_GET['price']) ? htmlspecialchars($_GET['price']) : '' ?>">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
        <table id="productTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['price']) && $_GET['price'] !== '') {
                    $filterKeyword = $_GET['price'];
                    $filteredProducts = array_filter($products, function ($product) use ($filterKeyword) {
                        return stripos($product['id'], $filterKeyword) !== false
                            || stripos($product['name'], $filterKeyword) !== false
                            || stripos($product['price'], $filterKeyword) !== false
                            || stripos($product['quantity'], $filterKeyword) !== false;
                    });
                    $filteredProducts = array_values($filteredProducts); // reset index
                } else {
                    $filteredProducts = $products;
                }

                foreach ($filteredProducts as $index => $product) {
                    echo "<tr>";
                    echo "<td>" . ($index + 1) . "</td>";
                    echo "<td>" . $product['id'] . "</td>";
                    echo "<td>" . $product['name'] . "</td>";
                    echo "<td>" . $product['price'] . "</td>";
                    echo "<td>" . $product['quantity'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let table = new DataTable('#productTable');
    </script>
</body>

</html>