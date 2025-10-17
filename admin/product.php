<?php
    require "../Database/db.php";
    session_start();
    $success = $error = "";
    if(!isset($_SESSION["admin_id"])) {
        header("Location: admin_login.php");
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $description = $_POST["description"];
            $price = $_POST["price"];
            $image = $_POST["image"];

            if(empty($name) || empty($description) || empty($price) || empty($image)) {
                $error = "All fields are required!!!";
            } else {
                $sql = "INSERT INTO products (name, description, image, price) VALUES (?, ?, ?, ?)";
                $stmt = $connection ->prepare($sql);
                $stmt -> bind_param("ssss", $name, $description, $image, $price);

                if($stmt -> execute()) {
                    $success = "Successfully added the product...";
                    header("Location: admin_page.php");
                } else {
                    $error = "Failed to add product" . $stmt -> error;
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../styles/product.css">
</head>
<body>
    <nav>
        <h1><a href="admin_page.php">Ecommerce Website</a></h1>
    </nav>
    <div class="container">
        <h1>Add Products</h1>
        <form method="POST">
            <input type="text" name="name" placeholder="Enter the product name">
            <textarea name="description" placeholder="Enter the product Description"></textarea>
            <input type="url" name="image" placeholder="Enter the Image URL">
            <input type="text" name="price" placeholder="Enter the price..">
            <button type="Submit" class="btn">Submit</button>
        </form>
         <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
        <?php if($success): ?><p class="success"><?= $success ?></p><?php endif;  ?>
    </div>
</body>
</html>