<?php
    require "../../models/db.php";
    require "../../models/validate.php";
    session_start();
    $error = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        if(empty($email) || empty($password)) {
            $error = "All fields are requied!!";
        } else {
            $validate = new Validate();
            $response = $validate -> login($email, $password);
            
            if(strcmp($response, "OK" === 0)) {   
                $database = new Database();
                $check_email = $database -> check_email($email, "admin");
                if($check_email > 0) {
                    $result = $database -> get_admin($email, $password);
                    if(strcmp($result, "OK") === 0) {
                        $success = "Successfully logged in...";
                        echo "<script>
                                alert('Login successful!');
                                window.location.href = '../admin/admin_page.php';
                            </script>";
                        exit;
                    } else {
                        $error = "Failed to Login.." . $result;
                    }
                } else {
                    $error = "No Mail Found!!";
                }
            } else {
                $error = "Failed to login: " . $response;
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Page</title>
    <link rel="stylesheet" href="../../public/styles/style.css">
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <div class="container">
        <h1 style="text-align: center;">Admin Login Page</h1>
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
            </form>
        <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    </div>
</body>
</html>