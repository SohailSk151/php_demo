<?php
    require "validate.php";
    require "./Database/db.php";
    session_start();
    $error = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        if( empty($email) || empty($password) ) {
            $error = "All fields are required!!";
        } else {
            $validate = new Validate();
            $response = $validate -> login($email, $password);
            
            if(strcmp($response, "OK") === 0) {
                $database = new Database();
                $check_email = $database -> check_email($email, "users");
                if($check_email > 0) {
                    $result = $database -> get_user($email, $password);
                    if(strcmp($result, "OK") === 0) {
                        $success = "Successfully logged in...";
                        header("Location: welcome.php");
                    } else {
                        $error = "Failed to Login.." . $result;
                    }
                }
            }
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
                <label>Email</label>
                <input type="email" name="email" placeholder="Email">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Login</button>
                <p>Don't have a account? <a href="register.php">Register</a></p>
            </form>
        <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    </div>
</body>
</html>