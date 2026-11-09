<?php
    session_start();

    include 'connect.php';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname   = "wayangfm_db";

    $login_email = $_POST['email'];
    $login_pass  = $_POST['password'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo("<script>alert('Database connection failed!')</script>");
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT user_id, username, pass FROM accounts WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $login_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        
        if ($login_pass === $user['pass']) {
            
            $_SESSION['user_id']  = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            session_write_close();

            header("Location: http://localhost/wayang_fm/dashboard.php");
            exit;

        } else {
            echo "<script>alert('Invalid password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No account found with that email.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
?>