<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $province = $_POST['province'];
    $email = $_POST['email'];

    // ตรวจสอบการยืนยันรหัสผ่าน
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // ตรวจสอบความซับซ้อนของรหัสผ่าน
    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {
        die("Password must be at least 8 characters long and contain at least one number, one uppercase and one lowercase letter.");
    }

    // เข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบอีเมล์ซ้ำ
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        die("Email already exists.");
    }

    // บันทึกข้อมูลในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO users (username, password, firstname, lastname, gender, age, province, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiis", $username, $hashed_password, $firstname, $lastname, $gender, $age, $province, $email);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: index.html");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
