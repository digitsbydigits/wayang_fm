<?php
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: welcome.php");
    exit;
}

if (isset($_POST['join_code']) && !empty(trim($_POST['join_code']))) {
    
    $input_code = strtoupper(trim($_POST['join_code']));
    $stmt = $conn->prepare("SELECT room_code FROM rooms WHERE room_code = ? LIMIT 1");
    $stmt->bind_param("s", $input_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: theater.php?room=" . $input_code);
        exit;
    } else {
        echo "<script>
                alert('Room not found or has been closed. 🍿');
                window.location.href='dashboard.php';
              </script>";
        exit;
    }

    $stmt->close();
} else {
    header("Location: dashboard.php");
    exit;
}

$conn->close();
?>