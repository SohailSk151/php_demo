<?php
    require "./Database/db.php";
    $success = $error = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        if (empty($name) || empty($email) || empty($password)) {
            $error = "All fields are required!!!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?);";
            $stmt = $connection -> prepare($sql);
            $stmt -> bind_param("sss", $name, $email, $hashed_password);

            if($stmt -> execute()) {
                $success = "Successfully added the user into Site...";
                header("Location: login.php");
            } else {
                $error = "Error in added user into site" . $stmt -> error;
            }

            $stmt -> close();
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registration Page</title>
        <link rel="stylesheet" href="./styles/style.css">
    </head>
    <body>
        <nav>
            <h1><a href="welcome.php">Ecommerce Website</a></h1>
        </nav>
        <div class="container">
            <h1>Enroll your self into our site</h1>
            <form method="POST">
                <input type="text" name="name" placeholder="Full Name">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Register</button>
                <p>Already have an account? <a href="login.php">Login</a></p>
            </form>
            <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
            <?php if($success): ?><p class="success"><?= $success ?></p><?php endif;  ?>
        </div>
    </body>
</html>