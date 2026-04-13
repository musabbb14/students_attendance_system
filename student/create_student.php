<?php
session_start();
include "../config/db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $reg = mysqli_real_escape_string($conn, $_POST['reg_number']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $level = mysqli_real_escape_string($conn, $_POST['level']);

    // We force 'student' as the role so the login.php can find it
    $sql = "INSERT INTO users (reg_number, name, username, password, role, level)
            VALUES ('$reg', '$name', '$username', '$password', 'student', '$level')";

    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Student Created Successfully'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="form-card">
    <h2>Create Student</h2>
    <form method="POST">
        <input type="text" name="reg_number" placeholder="Registration Number" required>
        <input type="text" name="name" placeholder="Student Name" required>
        <select name="level" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
            <option value="">Select Level</option>
            <option value="100">100 Level</option>
            <option value="200">200 Level</option>
            <option value="300">300 Level</option>
            <option value="400">400 Level</option>
        </select>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>
</body>
</html>