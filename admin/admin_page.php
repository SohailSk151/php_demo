<?php
require "../Database/db.php";
session_start();
$error = "";

if(!isset($_SESSION["admin_id"])){
    session_destroy();
    header("Location: admin_login.php");
    exit();
} else {
    $database = new Database();
    $result = $database->get_products();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../styles/admin_page.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <div class="container">
        <h2>Product List</h2>
        <table cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th colspan="2" style="text-align: center">Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $i++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>₹" . htmlspecialchars($row['price']) . "</td>";

                        // Display uploaded image
                        $imgPath = htmlspecialchars($row['image']);
                        if(file_exists($imgPath)) {
                            echo "<td><img src='" . $imgPath . "' alt='Product Image' width='80'></td>";
                        } else {
                            echo "<td>No image</td>";
                        }

                        echo "<td><a href='edit.php?id=" . $row['id'] . "'>Edit</a></td>";
                        echo "<td><a href='delete.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this product?');\">Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
