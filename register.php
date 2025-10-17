<?php
    require "./Database/db.php";
    require "validate.php";
    require "sendmail.php";

    $success = $error = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        if (empty($name) || empty($email) || empty($password)) {
            $error = "All fields are required!!!";
        } else {
            $validate = new Validate();
            $response = $validate -> registration($name, $email, $password);

            if(strcmp("OK", $response) === 0) {
                /* Check for duplicate email id */
                $database = new Database();
                $check = $database -> check_email($email, "users");
                if ($check > 0) {
                    $error = "Email ID already exists!!!";
                }

                $result = $database -> add_user($name, $email, $password);
                if($result) {
                    $success = "Successfully added the user into Site...";
                    $message = '<h2>Welcome to <b>Ecommerce Website</b>!</h2>
                                <p>Hi <b>' . htmlspecialchars($name) . '</b>,</p>
                                <p>We’re excited to have you on board. Your registration has been successfully completed.</p>
                                <p>You can now log in to your account and start exploring tailor-made fashion options designed just for you.</p>
                                <p>
                                    <a href="http://localhost/yourproject/login.php" 
                                    style="display:inline-block; background:#4CAF50; color:white; padding:10px 20px; 
                                            text-decoration:none; border-radius:5px;">
                                    Login to Your Account
                                    </a>
                                </p>
                                <p>If you didn’t register for this account, please ignore this email.</p>
                                <br>
                                <p>Best regards,<br>
                                <b>Ecommerse Web Site</b></p>';
                    send_mail($name, $email, 
                    "🎉 Welcome to Ecommerce Website – Registration Successful!",
                    $message);

                    echo "<script>
                            alert('Registration successful!');
                            window.location.href = 'login.php';
                        </script>";
                    exit;
                } else {
                    $error = "Error in added user into site" . $result;
                }
    
                $stmt -> close();
            } else {
                $error = $response;
            }
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
                <label for="name">Full Name</label>
                <input type="text" name="name" placeholder="Full Name">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Email">
                <label for="email">Password</label>
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Register</button>
                <p>Already have an account? <a href="login.php">Login</a></p>
            </form>
            <?php if($error): ?><p class="error" style="color: red;"><?= $error ?></p><?php endif; ?>
            <?php if($success): ?><p class="success" style="color: green;"><?= $success ?></p><?php endif;  ?>
        </div>
    </body>
</html>