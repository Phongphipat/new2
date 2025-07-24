<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Loop for Show Product</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

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
        ['id' => 1004, 'name' => 'Mango', 'price' => 45, 'quantity' => 60],
        ['id' => 1005, 'name' => 'Mangosteen', 'price' => 30, 'quantity' => 90],
        ['id' => 1006, 'name' => 'Watermelon', 'price' => 25, 'quantity' => 40],
        ['id' => 1007, 'name' => 'Pineapple', 'price' => 35, 'quantity' => 60],
        ['id' => 1008, 'name' => 'Orange', 'price' => 20, 'quantity' => 150],
        ['id' => 1009, 'name' => 'Grape', 'price' => 80, 'quantity' => 25],
        ['id' => 1010, 'name' => 'Papaya', 'price' => 28, 'quantity' => 55],
        ['id' => 1011, 'name' => 'Lychee', 'price' => 70, 'quantity' => 30],
        ['id' => 1012, 'name' => 'Rambutan', 'price' => 70, 'quantity' => 80],
        ['id' => 1013, 'name' => 'Longan', 'price' => 55, 'quantity' => 45],
        ['id' => 1014, 'name' => 'Guava', 'price' => 22, 'quantity' => 100],
        ['id' => 1015, 'name' => 'Coconut', 'price' => 70, 'quantity' => 35],
    ];
    ?>
    <div class="container mt-5">
        <h1>Product List</h1>
        <form method="post" class="mb-3">
            <div>
                <input type="number" name="price" placeholder="Enter Price" class="form-control mb-2" required />
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        <?php
        if (isset($_POST['price'])) {
            $filterPrice = $_POST['price'];
            $filterProducts = array_filter($products, function ($product) use ($filterPrice) {
                return $product['price'] == $filterPrice;
            });
            $filterProducts = array_values($filterProducts); // reset index
        } else {
            $filterProducts = $products;
        }
        ?>

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
                <?php foreach ($filterProducts as $index => $product): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $product['id'] ?></td>
                        <td><?= $product['name'] ?></td>
                        <td><?= $product['price'] ?></td>
                        <td><?= $product['quantity'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery (โหลดก่อน) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#productTable').DataTable();
        });
    </script>

</body>

</html>