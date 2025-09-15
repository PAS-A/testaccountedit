<?php
session_start();
include 'db_connect.php';

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: showdata.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
