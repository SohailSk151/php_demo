<?php
require "../Database/db.php";
session_start();
$error = "";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $database = new Database();
    $product = $database -> get_product_by($id);
    //$database -> close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    if(isset($name) && isset($description) && isset($image) && isset($price)) {
        
        $database = new Database();
        $update = $database -> edit_product($id, $price, $description, $image, $price);

        if ($update) {
            $subject = 'Product Updated – ' . htmlspecialchars($name);
            $message = '
                <h2>Product Updated Successfully!</h2>
                <p>Hello <b>' . htmlspecialchars($adminName) . '</b>,</p>
                <p>The following product has been updated in the system:</p>

                <ul>
                    <li><b>Name:</b> ' . htmlspecialchars($productName) . '</li>
                    <li><b>Updated Fields:</b> ' . htmlspecialchars($updatedFields) . '</li>
                    <li><b>Updated On:</b> ' . date("d M Y, h:i A") . '</li>
                </ul>

                <p>You can view all product updates in the admin dashboard.</p>

                <a href="http://localhost/admin/admin_page.php"
                style="display:inline-block; background-color:#ffc107; color:black; 
                        text-decoration:none; padding:10px 20px; border-radius:5px;">
                Go to Products
                </a>

                <br><br>
                <p>Best regards,<br><b>Ecommerce Website Maker Team</b></p>
            ';
            send_mail($name, $email, $subject, $message);
                
            echo "<script>
                    alert('Product Edited succesfully!');
                    window.location.href = 'admin_page.php';
                </script>";
            exit;
        } else {
            echo "Error updating record: " . $connection->error;
        }
    } else {
        $error = "All fields are required!!!";
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="../styles/product.css">
</head>
<body>
    <nav>
        <h1><a href="admin_page.php">Ecommerce Website</a></h1>
    </nav>
    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
            <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
            <input type="text" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
            <input type="url" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
            <span>
                <button type="submit">Update</button>
                <button><a href="admin_page.php">Cancel</a></button>
            </span>
        </form>
    </div>
</body>
</html>
