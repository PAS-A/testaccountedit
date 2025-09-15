<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (hash_equals($user['password'], hash('sha256', $password))) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] == 'admin') {
                header('Location: showdata.php');
            } else {
                header('Location: customer_dashboard.php');
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }
}
?>
