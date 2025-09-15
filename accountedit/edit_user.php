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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $gender = $_POST['gender'];
        $age = $_POST['age'];
        $province = $_POST['province'];
        $email = $_POST['email'];

        $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, gender = ?, age = ?, province = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssissi", $firstname, $lastname, $gender, $age, $province, $email, $id);

        if ($stmt->execute()) {
            header("Location: showdata.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
</head>
<body>
    <h2>Edit Customer</h2>
    <form method="POST">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>" required><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>" required><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender">
            <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
        </select><br>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" value="<?php echo $user['age']; ?>" required><br>

        <label for="province">Province:</label>
        <input type="text" id="province" name="province" value="<?php echo $user['province']; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
