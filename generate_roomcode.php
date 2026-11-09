<?php
//might be redundant tbh.. but I need this for the AJAX fetch to work 
// (so the page doesn't reload AND so that room thingy works) - pidot :p
    session_start();
    include 'connect.php';

    if (!isset($_SESSION['user_id'])) {
        die("Unauthorized!");
    }

    $hostId = $_SESSION['user_id'];

    $cleanup = $conn->prepare("DELETE FROM rooms WHERE host_id = ?");
    $cleanup->bind_param("i", $hostId);
    $cleanup->execute();

    $newCode = strtoupper(bin2hex(random_bytes(3)));
    $hostId = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO rooms (room_code, host_id) VALUES (?, ?)");
    $stmt->bind_param("si", $newCode, $hostId);

    if ($stmt->execute()) {
        echo $newCode; 
    } else {
        echo "ERROR";
    }
?>