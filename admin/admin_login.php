<?php
    require "../Database/db.php";
    session_start();
    $error = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        if(empty($email) || empty($password)) {
            $error = "All fields are requied!!";
        } else {
            $sql = "SELECT * FROM admin WHERE email = ?;";
            $stmt = $connection->prepare($sql);
            $stmt -> bind_param("s", $email);
            $stmt -> execute();
            $result = $stmt->get_result();
            $connection = $result -> fetch_assoc();
            
            if($connection && password_verify( $password, $connection["password"] )) {
                $_SESSION["admin_id"] = $connection["id"];
                $_SESSION["admin_name"] = $connection["name"];
                echo "Succesfully logged in";
                header("Location: admin_page.php");
                exit();
            } else {
                $error = "Email is invalid!!";
            }
            $stmt ->close();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Page</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <nav>
        <h1><a href="../welcome.php">Ecommerce Website</a></h1>
    </nav>
    <div class="container">
        <h1>Admin Login Page</h1>
            <form method="POST">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Login</button>
            </form>
        <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    </div>
</body>
</html>