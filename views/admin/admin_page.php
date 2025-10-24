<?php
require "../../models/db.php";
session_start();
$error = "";

if(!isset($_SESSION["admin_id"])){
    session_destroy();
    header("Location: ../auth/admin_login.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <?php include '../partials/navbar.php'; ?>

    <div class="container">
        <h2 class="text-center">Product List</h2>
        <table cellpadding="8" cellspacing="0" class="table text-center table-bordered table-hover table-responsive-md ">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col">Image</th>
                    <th scope="col" colspan="2" >Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($result && $result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<th scope='row'>" . $i++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['price']) . "</td>";

                            // Display uploaded image
                            echo "<td>";
                                $imgPath = $row['image']; // get image path from DB
                                $imgPathSafe = str_replace(' ', '%20', $imgPath); // handle spaces

                                if (file_exists($imgPath)) {
                                    echo "<figure>
                                            <img class='rounded' src='" . htmlspecialchars($imgPathSafe, ENT_QUOTES, 'UTF-8') . "' 
                                                alt='Product Image' 
                                                style='width: 250px; height: 250px;'>
                                        </figure>";
                                } else {
                                    echo "No image found";
                                }
                            echo "</td>";

                            echo "<td  role='alert'><a class='btn btn-sm btn-secondary text-white' href='edit.php?id=" . $row['id'] . "'>Edit</a></td>";
                            echo "<td  role='alert'><a class='btn btn-sm btn-danger'  href='delete.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this product?');\">Delete</a></td>";
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
