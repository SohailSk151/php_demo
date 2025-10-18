<?php
require "../../models/db.php";
session_start();

$database = new Database();
$result = $database->get_products();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>
    <link rel="stylesheet" href="../../public/styles/welcome.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>

    <?php if (isset($_SESSION['user_name'])): ?>
        <h1>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
    <?php endif; ?>

    <h2 style="text-align: center; padding-top: 10px;">Available Products</h2>
    <div class="products-container">
        <div class="products">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-card'>";

                    // Display product image from uploads folder
                    $imgPath = htmlspecialchars($row['image']);
                    if (file_exists($imgPath)) {
                        echo "<figure><img src='" . $imgPath . "' alt='Product Image' style='width: 250px; height: 250px;'></figure>";
                    } else {
                        echo "<figure><p>No Image</p></figure>";
                    }

                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p class='price'>₹" . htmlspecialchars($row['price']) . "</p>";
                    echo "<button type='button' class='btn'>Buy Now</button>";
                    echo "</div>";
                }
            } else {
                echo "<p class='no-products'>No products found.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
