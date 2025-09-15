<?php
session_start();
include 'db_connect.php';

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

// แสดงข้อมูลของลูกค้าที่มี role = 'customer'
$query = "SELECT id, username, firstname, lastname, gender, age, province, email FROM users WHERE role = 'customer'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Accounts</title>
</head>
<body>
    <h2>Customer Accounts</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Province</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['firstname']; ?></td>
            <td><?php echo $row['lastname']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td><?php echo $row['age']; ?></td>
            <td><?php echo $row['province']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <a href="add_user.php">Add New Customer</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>
