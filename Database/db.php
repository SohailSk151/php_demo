<?php
    $host = "localhost";
    $user = "root";
    $pass = "SohailSk@19";
    $dbname = "user_auth";

    $connection = mysqli_connect($host, $user, $pass, $dbname);

    if ($connection -> connect_error) {
        die("Connection Failed: " . $user -> mysqli_connect_error());
    }
?>