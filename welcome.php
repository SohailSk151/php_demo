<?php
    require "./Database/db.php";
    session_start();
    $database = new Database();
    $result = $database -> get_products();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>
    <link rel="stylesheet" href="./styles/welcome.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2 style="text-align: center; padding-top: 10px;">Available Products</h2>
    <div class="products-container">
        <div class="products">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-card'>";
                    echo "<figure><img src='" . htmlspecialchars($row['image']) . "' alt='Product Image' style='width: 250px; height: 250px;'></figure>";
                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p class='price'>" . htmlspecialchars($row['price']) . "</p>";
                    echo "<button type='button' class='btn'>Buy Now</button>";
                    echo "</div>";
                }
            } else {
                echo "<p class='no-products'>No Products found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>