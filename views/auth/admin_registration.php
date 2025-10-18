<?php
    require "../../models/db.php";
    require "../../models/validate.php";
    require "../../models/sendmail.php";
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
                $check = $database -> check_email($email, "admin");
                if ($check > 0) {
                    $error = "Email ID already exists!!!";
                }

                $result = $database -> add_admin($name, $email, $password);
                if($result) {
                    $success = "Successfully added the admin into System...";
                    $message ='
                                <h2>Welcome, <b>' . htmlspecialchars($adminName) . '</b>!</h2>
                                <p>Your <b>Admin account</b> for <b>Custom Garment Maker</b> has been successfully created.</p>
                                
                                <h4>Account Details:</h4>
                                <ul>
                                    <li><b>Email:</b> ' . htmlspecialchars($adminEmail) . '</li>
                                    <li><b>Role:</b> Administrator</li>
                                </ul>

                                <p>You can now log in to the admin dashboard and manage users, tailors, and orders.</p>
                                
                                <p style="margin-top:20px;">
                                    <a href="http://localhost/custom_garment_maker/admin/admin_login.php"
                                    style="display:inline-block; background-color:#007bff; color:white; 
                                            text-decoration:none; padding:10px 20px; border-radius:5px;">
                                    Go to Admin Dashboard
                                    </a>
                                </p>

                                <p>Please keep your login credentials secure and do not share them with anyone.</p>

                                <br>
                                <p>Warm regards,<br>
                                <b>The Custom Garment Maker Team</b></p>
                            ';
                    send_mail($name, $email, "Admin Registration Successful – Ecommerce Website", $message);
                    echo "<script>
                            alert('Registration successful!');
                            window.location.href = '../admin/admin_login.php';
                        </script>";
                    exit;
                } else {
                    $error = "Error in adding admin to the system.." . $result;
                }    
            } else {
                $error = $response;
            } 
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Registration Page</title>
        <link rel="stylesheet" href="../../public/styles/style.css">
    </head>
    <body>
        <nav>
            <h1><a href="admin_page.php">Ecommerce Website</a></h1>
        </nav>
        <div class="container">
            <h1 style="text-align: center; padding-bottom: 10px">Enroll a new Admin</h1>
            <form method="POST">
                <input type="text" name="name" placeholder="Full Name">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Register</button>
            </form>
            <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
            <?php if($success): ?><p class="success"><?= $success ?></p><?php endif;  ?>
        </div>
    </body>
</html>