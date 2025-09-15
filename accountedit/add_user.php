<?php
session_start();
include 'db_connect.php';

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

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

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {
        die("Password must be at least 8 characters long and contain at least one number, one uppercase, and one lowercase letter.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบว่ามี username หรือ email ซ้ำหรือไม่
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        die("Email already exists.");
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, firstname, lastname, gender, age, province, email, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'customer')");
    $stmt->bind_param("sssssiis", $username, $hashed_password, $firstname, $lastname, $gender, $age, $province, $email);

    if ($stmt->execute()) {
        header("Location: showdata.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer</title>
</head>
<body>
    <h2>Add New Customer</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required><br>

        <label for="province">Province:</label>
        <input type="text" id="province" name="province" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <input type="submit" value="Add Customer">
    </form>
</body>
</html>
