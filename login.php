<?php
session_start();
include "config/db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    if($username == "student" && $password == "student123") {
        $_SESSION['user_id'] = 0;
        $_SESSION['role'] = "student";
        $_SESSION['name'] = "General Student Access";
        header("Location: student/view_attendance.php");
        exit();
    }

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        if($user['role'] == "admin"){
            header("Location: admin/dashboard.php");
        } elseif($user['role'] == "teacher"){
            header("Location: teacher/dashboard.php");
        } else {
            echo "<script>alert('Error: Role not recognized.'); window.location.href='login.php';</script>";
        }
        exit();
    } else {
        echo "<script>alert('Invalid Username or Password'); window.location.href='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - School Attendance System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="login-card">
    <img src="images/logo.jpg" class="logo" alt="School Logo">
    <h2>School Attendance System</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
<script src="js/script.js"></script>
</body>
</html>