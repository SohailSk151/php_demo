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
            header("Location: admin_page.php");
            exit();
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
