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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <?php include '../partials/navbar.php'; ?>
    <div class="container w-25 p-3 justify-content-center border shadow rounded-3" style="width: 30%;">
        <h1 class="text-center display-4 font-weight-bold">Login Page</h1>
            <form method="POST" class="d-flex flex-column gap-1 container" style="width: 85%;">
                <label>Email</label>
                <input  type="email" name="email" placeholder="Email">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password">
                <ol>
                    <li>Has at least one lowercase letter</li>
                    <li>Has at least one uppercase letter</li>
                    <li>Has at least one digit</li>
                    <li>Has at least one special character (from the given list)</li>
                    <li>Has a minimum length of 8 characters</li>
                </ol>
                <button class="btn btn-success" type="submit">Login</button>
                <p>Don't have a account? <a href="register.php">Register</a></p>
            </form>
        <?php if($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    </div>
</body>
</html>