<?php
require "../../models/db.php";
require "../../sendmail.php";
session_start();

$success = $error = "";

// Redirect if admin not logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    // --- File Upload Handling ---
    $target_dir = "../../public/uploads/productImages/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
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
    if (
        $imageFileType != "jpg" &&
        $imageFileType != "png" &&
        $imageFileType != "jpeg" &&
        $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Upload validation
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // --- Validate form fields ---
    if (empty($name) || empty($description) || empty($price) || empty($target_file)) {
        $error = "All fields are required!!!";
    } else {
        $database = new Database();
        $result = $database->add_products($name, $description, $target_file, $price);

        if ($result) {
            // Fetch all users to send mail
            $users = $database->get_all_users();

            $subject = 'New Product Added – ' . htmlspecialchars($name) . ' | Ecommerce Website';
            $message = '
                <h2>Product Added Successfully!</h2>
                <p>Hello,</p>
                <p>A new product has been added to the <b>Ecommerce Website Maker</b> inventory.</p>

                <h4>Product Details:</h4>
                <ul>
                    <li><b>Name:</b> ' . htmlspecialchars($name) . '</li>
                    <li><b>Description:</b> ' . htmlspecialchars($description) . '</li>
                    <li><b>Price:</b> ₹' . htmlspecialchars($price) . '</li>
                </ul>

                <p>You can review or manage this product anytime in the admin panel.</p>

                <a href="http://localhost/user_auth/welcome.php"
                style="display:inline-block; background-color:#28a745; color:white;
                        text-decoration:none; padding:10px 20px; border-radius:5px;">
                View Product List
                </a>

                <br><br>
                <p>Best regards,<br><b>Ecommerce Website Maker Team</b></p>
            ';

            // --- Send notification email to all users ---
            if ($users && $users->num_rows > 0) {
                while ($row = $users->fetch_assoc()) {
                    $userName = $row['name'];
                    $userEmail = $row['email'];
                    send_mail($userName, $userEmail, $subject, $message);
                }
            }

            echo "<script>
                    alert('Product added successfully!');
                    window.location.href = 'admin_page.php';
                  </script>";
            exit;
        } else {
            $error = "Failed to add product.";
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
    <link rel="stylesheet" href="../../public/styles/product.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <div class="container">
        <h1 style="text-align: center;">Add Products</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Name</label>
            <input type="text" name="name" placeholder="Enter the product name">
            <label for="description">Description</label>
            <textarea name="description" placeholder="Enter the product Description"></textarea>
            <label for="file">Select file to upload:</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <label for="price">Price</label>
            <input type="text" name="price" placeholder="Enter the price..">
            <button type="Submit" class="btn">Submit</button>
        </form>
         <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
        <?php if($success): ?><p class="success"><?= $success ?></p><?php endif;  ?>
    </div>
</body>
</html>