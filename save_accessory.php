<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    $accessory = $_POST['accessory'];
    $user = $_SESSION['username'];

    $stmt = $conn->prepare("UPDATE accounts SET current_accessory = ? WHERE username = ?");
    $stmt->bind_param("ss", $accessory, $user);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
}
$conn->close();
?>