<?php
session_start();
include "../config/db.php";

// Get the logged-in teacher's assigned course
$my_course_id = $_SESSION['course_id'];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $reg = mysqli_real_escape_string($conn, $_POST['reg_number']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Automatically assign student to the Teacher's course_id
    $sql = "INSERT INTO users (reg_number, name, username, password, role, course_id)
            VALUES ('$reg', '$name', '$username', '$password', 'student', '$my_course_id')";

    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Student added to your class!');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="../css/style.css"></head>
<body>
<div class="form-card">
    <h2>Teacher: Add Student to My Class</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Student Name" required>
        <input type="text" name="reg_number" placeholder="Reg Number" required>
        <input type="text" name="username" placeholder="Student Username" required>
        <input type="password" name="password" placeholder="Student Password" required>
        <button type="submit">Add Student</button>
    </form>
    <a href="dashboard.php" class="back-btn">Back to Marking</a>
</div>
</body>
</html>