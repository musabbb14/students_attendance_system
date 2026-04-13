<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher'){
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="dashboard-container">
    <h2>Teacher Dashboard</h2>
    <p>Welcome, <?php echo $_SESSION['name']; ?></p>

    <div class="menu">
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="manage_students.php">Manage Students</a>
        <a href="view_records.php">View Records</a> 
        <a href="../logout.php" class="logout">Logout</a>
    </div>

</div>

</body>
</html>