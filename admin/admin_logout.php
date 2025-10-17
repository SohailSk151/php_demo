<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: admin_page.php");
    echo "<script>
            alert('Loged Out succesfully!');
            window.location.href = 'admin_login.php';
        </script>";
    exit;
?>