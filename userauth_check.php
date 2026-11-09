<?php
    include 'connect.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: welcome.php");
        exit;
    }
?>