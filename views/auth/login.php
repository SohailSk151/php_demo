<?php
    require "../../models/validate.php";
    require "../../models/db.php";
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
                        echo "<script>
                                alert('✅ Login successful!');
                                window.location.href = '../user/welcome.php';
                            </script>";
                        exit;
                    } else {
                        $error = "Failed to Login.." . $result;
                    }
                }
            } else {
                $error = $response;
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
    <link rel="stylesheet" href="../../public/styles/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <div class="container">
        <h1 style="text-align: center;">Login Page</h1>
            <form method="POST">
                <label>Email</label>
                <input type="email" name="email" placeholder="Email">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password">
                <ol>
                    <li>Has at least one lowercase letter</li>
                    <li>Has at least one uppercase letter</li>
                    <li>Has at least one digit</li>
                    <li>Has at least one special character (from the given list)</li>
                    <li>Has a minimum length of 8 characters</li>
                </ol>
                <button type="submit">Login</button>
                <p>Don't have a account? <a href="register.php">Register</a></p>
            </form>
        <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    </div>
</body>
</html>