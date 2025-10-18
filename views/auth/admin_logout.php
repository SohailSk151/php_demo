<?php
    session_start();
    session_unset();
    session_destroy();
    echo "<script>
            alert('Loged Out succesfully!');
            window.location.href = 'admin_login.php';
        </script>";
    exit;
?>