<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname   = "wayangfm_db";

    $reg_name  = $_POST['username'];
    $reg_email = $_POST['email'];
    $reg_pass  = $_POST['password'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT 1 FROM Accounts WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $reg_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Email already exists! Log in instead.');
                window.location.href='http://localhost/wayang_fm/login.php';
              </script>";
        exit;
    } 

    $stmt = $conn->prepare("INSERT INTO Accounts (username, email, pass) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $reg_name, $reg_email, $reg_pass);

    if ($stmt->execute()) {
        echo "<script>
                alert('Account registered! Please log-in.');
                window.location.href='http://localhost/wayang_fm/login.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Registration unsuccessful.')</script>"; 
        die('Database creation failed : '. $stmt->error);
    }

    $stmt->close();
    $conn->close();
?>