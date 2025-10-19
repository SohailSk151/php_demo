<?php
require "../../models/db.php";
require "../../sendmail.php";
session_start();

$error = "";

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../auth/admin_login.php");
    exit();
}

// Fetch product details
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $database = new Database();
    $product = $database->get_product_by($id);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    // --- File Upload Handling ---
    $target_dir = "../../public/uploads/productImages/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = "";
    if (!empty($_FILES["fileToUpload"]["name"])) {
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image file
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow only image formats
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Upload file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            $target_file = $product["image"]; // keep old image if upload fails
        }
    } else {
        $target_file = $product["image"]; // retain old image if no new one is selected
    }

    // --- Validate form fields ---
    if (empty($name) || empty($description) || empty($price) || empty($target_file)) {
        $error = "All fields are required!!!";
    } else {
        $database = new Database();
        $update = $database->edit_product($id, $name, $description, $target_file, $price);

        if ($update) {
            $result = $database->get_all_users();
            $subject = 'Product Updated – ' . htmlspecialchars($name);
            $message = '
                <h2>Product Updated Successfully!</h2>
                <p>Hello <b>' . htmlspecialchars($_SESSION["admin_name"]) . '</b>,</p>
                <p>The following product has been updated in the system:</p>

                <ul>
                    <li><b>Name:</b> ' . htmlspecialchars($name) . '</li>
                </ul>

                <p>You can view all product updates in the admin dashboard.</p>

                <a href="http://localhost/user_auth/welcome.php"
                style="display:inline-block; background-color:#ffc107; color:black; 
                        text-decoration:none; padding:10px 20px; border-radius:5px;">
                Go to Products
                </a>

                <br><br>
                <p>Best regards,<br><b>Ecommerce Website Maker Team</b></p>
            ';

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $userName = $row['name'];
                    $userEmail = $row['email'];
                    send_mail($userName, $userEmail, $subject, $message);
                }
            }

            echo "<script>
                    alert('Product Edited successfully!');
                    window.location.href = 'admin_page.php';
                  </script>";
            exit;
        } else {
            echo "Error updating record.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="../../public/styles/product.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">

            <label for="description">Description</label>
            <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="price">Price</label>
            <input type="text" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">

            <label for="file">Select file to upload:</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <p>Current Image: <img src="<?php echo htmlspecialchars($product['image']); ?>" width="100"></p>

            <span>
                <button type="submit" class="btn">Update</button>
                <button><a href="admin_page.php">Cancel</a></button>
            </span>
        </form>

        <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    </div>
</body>
</html>
