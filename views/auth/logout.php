<?php
    session_start();
    session_unset();
    session_destroy();
    echo "<script>
                alert('Logged out succesfully!');
                window.location.href = '../user/welcome.php';
        </script>";
    exit;
?>