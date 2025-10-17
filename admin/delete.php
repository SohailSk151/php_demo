<?php
require "../Database/db.php";
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $database = new Database();
    $result = $database -> delete_products($id);

    if ($result) {
        $subject = 'Product Deleted – ' . htmlspecialchars($name);
        $message = '
            <h2>Product Deleted</h2>
            <p>Hello <b>' . htmlspecialchars($adminName) . '</b>,</p>
            <p>The following product has been removed from the system:</p>

            <ul>
                <li><b>Name:</b> ' . htmlspecialchars($productName) . '</li>
                <li><b>ID:</b> ' . htmlspecialchars($productId) . '</li>
            </ul>

            <p>If this deletion was unintentional, please review the activity logs in the admin dashboard.</p>

            <a href="http://localhost/admin/admin_page.php"
            style="display:inline-block; background-color:#dc3545; color:white; 
                    text-decoration:none; padding:10px 20px; border-radius:5px;">
            Review Products
            </a>

            <br><br>
            <p>Best regards,<br><b>Ecommerce Website Maker Team</b></p>
        ';
        send_mail($name, $email, $subject, $message);
        header("Location: admin_page.php");
        exit();
    } else {
        echo "Error deleting record: " . $connection->error;
    }
} else {
    header("Location: admin_page.php");
    exit();
}
?>
