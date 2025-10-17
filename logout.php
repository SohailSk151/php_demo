<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: welcome.php");
    echo "<script>
                alert('Logged out succesfully!');
                window.location.href = 'login.php';
        </script>";
    exit;
?>