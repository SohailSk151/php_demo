<?php
    require "../../models/db.php";
    require "../../models/validate.php";
    require "../../sendmail.php";
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
                else {
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
                                window.location.href = '../admin/admin_page.php';
                            </script>";
                        exit;
                    } else {
                        $error = "Error in adding admin to the system.." . $result;
                    }  
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <?php include '../partials/navbar.php'; ?>
        <div class="container w-25 p-3 border shadow-3 rounded-3" style="width: 30%;">
            <h1 class="text-center dispaly-3">Enroll a new Admin</h1>
            <form method="POST" class="d-flex flex-column gap-2 container" style="width: 85%;">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Full Name">
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
                <button type="submit" class="btn btn-success">Register</button>
            </form>
            <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
            <?php if($success): ?><p class="success"><?= $success ?></p><?php endif;  ?>
        </div>
    </body>
</html>