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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
<body>
    <?php include '../partials/navbar.php'; ?>

    <?php if (isset($_SESSION['user_name'])): ?>
        <h1>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
    <?php endif; ?>

    <h2 style="text-align: center; padding-top: 10px;">Available Products</h2>
    <div class="container">
        <div class="row">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col-12 col-sm-6 col-md-4 col-lg-3 mb-4'>";
                    echo "<div class='card h-100 shadow-sm'>"; // Full height + subtle shadow

                    $imgPath = htmlspecialchars($row['image']);
                    if (file_exists($imgPath)) {
                        echo "<img class='card-img-top' src='" . $imgPath . "' alt='Product Image' style='object-fit:cover; height:250px;'>";
                    } else {
                        echo "<div class='d-flex align-items-center justify-content-center bg-light' style='height:250px;'>No Image</div>";
                    }

                    echo "<div class='card-body d-flex flex-column'>";
                        echo "<h5 class='card-title'>" . htmlspecialchars($row['name']) . "</h5>";
                        echo "<p class='card-text flex-grow-1'>" . htmlspecialchars($row['description']) . "</p>";
                        echo "<p class='card-text fw-bold'>" . htmlspecialchars($row['price']) . "</p>";
                        echo "<a href='#' class='btn btn-info mt-auto'>Buy Now</a>"; // mt-auto pushes button to bottom
                    echo "</div>";

                    echo "</div>"; // card
                    echo "</div>"; 
                }
            } else {
                echo "<p class='no-products'>No products found.</p>";
            }
            ?>
        </div> 
    </div> 

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
