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
