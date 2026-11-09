<?php
    include 'connect.php';

    if (isset($_GET['room']) && isset($_SESSION['user_id'])) {
        $room_code = $_GET['room'];
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("SELECT host_id FROM rooms WHERE room_code = ?");
        $stmt->bind_param("s", $room_code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($room = $result->fetch_assoc()) {
            if ($room['host_id'] == $user_id) {
                $delete = $conn->prepare("DELETE FROM rooms WHERE room_code = ?");
                $delete->bind_param("s", $room_code);
                $delete->execute();
            }
        }

        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: dashboard.php");
        exit;
    }
?>