<?php
    require "../Database/db.php";
    require "../validate.php";
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
                    sleep(3);
                    header("Location: admin_page.php");
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
        <link rel="stylesheet" href="../styles/style.css">
    </head>
    <body>
        <nav>
            <h1><a href="admin_page.php">Ecommerce Website</a></h1>
        </nav>
        <div class="container">
            <h1 style="padding-bottom: 10px">Enroll a new Admin</h1>
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