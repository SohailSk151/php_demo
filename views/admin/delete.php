<?php
require "../../models/db.php";
require "../../sendmail.php";
session_start();

// Admin check
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../auth/admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $database = new Database();
    $product = $database->get_product_by($id);

    if (!$product) {
        // Product not found
        header("Location: admin_page.php");
        exit();
    }

    // Delete product from DB
    $result = $database->delete_products($id);

    // Delete image file from uploads folder
    if ($result && file_exists($product['image'])) {
        unlink($product['image']);
    }

    if ($result) {
        // Send notification email to all users
        $users = $database->get_all_users();
        $subject = 'Product Deleted – ' . htmlspecialchars($product['name']);
        $message = '
            <h2>Product Deleted</h2>
            <p>Hello,</p>
            <p>The following product has been removed from the system:</p>

            <ul>
                <li><b>Name:</b> ' . htmlspecialchars($product["name"]) . '</li>
                <li><b>ID:</b> ' . htmlspecialchars($product["id"]) . '</li>
            </ul>

            <p>If this deletion was unintentional, please review the activity logs in the admin dashboard.</p>

            <a href="http://localhost/user_auth/admin/admin_page.php"
            style="display:inline-block; background-color:#dc3545; color:white; 
                    text-decoration:none; padding:10px 20px; border-radius:5px;">
            Review Products
            </a>

            <br><br>
            <p>Best regards,<br><b>Ecommerce Website Maker Team</b></p>
        ';

        if ($users && $users->num_rows > 0) {
            while ($row = $users->fetch_assoc()) {
                $userName = $row['name'];
                $userEmail = $row['email'];
                send_mail($userName, $userEmail, $subject, $message);
            }
        }

        // Redirect back to admin page
        header("Location: admin_page.php");
        exit();
    } else {
        echo "Error deleting record.";
    }
} else {
    // No ID provided
    header("Location: admin_page.php");
    exit();
}
?>
