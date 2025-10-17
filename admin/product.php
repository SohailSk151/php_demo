<?php
    require "../Database/db.php";
    require "../sendmail.php";
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
                $database = new Database();
                $result = $database -> add_products($name, $description, $image, $price);
                if($result) {
                    $success = "Successfully added the product...";
                    $subject = 'New Product Added Successfully'. htmlspecialchars($name) .' – Ecommerce Website';
                    $message = '
                                <h2>Product Added Successfully!</h2>
                                <p>Hello <b>' . htmlspecialchars($adminName) . '</b>,</p>
                                <p>A new product has been added to the <b>Ecommerce Website Maker</b> inventory.</p>

                                <h4>Product Details:</h4>
                                <ul>
                                    <li><b>Name:</b> ' . htmlspecialchars($productName) . '</li>
                                    <li><b>Description:</b> ' . htmlspecialchars($productDesc) . '</li>
                                    <li><b>Price:</b> ₹' . htmlspecialchars($productPrice) . '</li>
                                </ul>

                                <p>You can review or manage this product anytime in the admin panel.</p>

                                <a href="http://localhost/user_auth/admin/admin_page.php"
                                style="display:inline-block; background-color:#28a745; color:white; 
                                        text-decoration:none; padding:10px 20px; border-radius:5px;">
                                View Product List
                                </a>

                                <br><br>
                                <p>Best regards,<br><b>Ecommerce Website Maker Team</b></p>
                            ';

                    send_mail($name, $email, $subject, $message);
                    echo "<script>
                            alert('Product added succesfully!');
                            window.location.href = 'admin_page.php';
                        </script>";
                    exit;
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