<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "wayangfm_db";

    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        echo "<script>alert('Database connection failed!');</script>";
        die("Connection failed: " . $conn->connect_error);
    }

    $sql_db = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql_db) === TRUE) {
        $conn->select_db($dbname);
    } else {
        die("Error creating database: " . $conn->error);
    }

    $sql_accounts = "CREATE TABLE IF NOT EXISTS accounts (
        user_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(60) NOT NULL UNIQUE,
        email VARCHAR(60) NOT NULL UNIQUE,
        pass VARCHAR(255) NOT NULL,
        current_accessory VARCHAR(60) NULL
    )";

    if ($conn->query($sql_accounts) === FALSE) {
        die("Error creating accounts table: " . $conn->error);
    }

    $sql_rooms = "CREATE TABLE IF NOT EXISTS rooms (
        room_id INT AUTO_INCREMENT PRIMARY KEY,
        room_code VARCHAR(10) UNIQUE NOT NULL,
        host_id INT(6) UNSIGNED NOT NULL, 
        FOREIGN KEY (host_id) REFERENCES accounts(user_id)
    )";

    if ($conn->query($sql_rooms) === FALSE) {
        die("Error creating rooms table: " . $conn->error);
    }

    $sql_insert_admin = "INSERT INTO accounts (username, email, pass)
                        SELECT * FROM (SELECT 'admin', 'test@root.com', 'Admin@022') AS tmp
                        WHERE NOT EXISTS (
                            SELECT 1 FROM accounts 
                            WHERE username = 'admin' OR email = 'test@root.com'
                        ) LIMIT 1";

    if ($conn->query($sql_insert_admin)) {
        echo "<script>alert('Database successfully configured.');
        window.location.href='http://localhost/wayang_fm/welcome.php';
        </script>";
    } else {
        die("Admin creation failed: " . $conn->error);
    }

    $conn->close();
?>