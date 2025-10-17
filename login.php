<?php
    require "./Database/db.php";
    session_start();
    $error = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        if( empty($email) || empty($password) ) {
            $error = "All fields are required!!";
        } else {
            $sql = "SELECT * FROM users WHERE email = ?;";
            $stmt = $connection ->prepare($sql);
            $stmt -> bind_param("s", $email);
            $stmt -> execute();
            $result = $stmt -> get_result();
            $connection = $result -> fetch_assoc();

            if ($connection && password_verify($password, $connection["password"])) {
                $_SESSION["user_id"] = $connection["id"];
                $_SESSION["user_name"] = $connection["name"];
                header("Location: welcome.php");
                exit();
            } else {
                $error = "Invalid Email or Password";
            }

            $stmt -> close();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="./styles/style.css">
</head>
<body>
    <nav>
        <h1><a href="welcome.php">Ecommerce Website</a></h1>
    </nav>
    <div class="container">
        <h1>Login Page</h1>
            <form method="POST">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Login</button>
                <p>Don't have a account? <a href="register.php">Register</a></p>
            </form>
        <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    </div>
</body>
</html>