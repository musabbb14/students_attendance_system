<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="dashboard-container">
    <h2>Admin Dashboard</h2>
    <p>Logged in as: <strong><?php echo $_SESSION['name']; ?></strong></p>

    <div class="menu">
        <a href="create_teacher.php">Assign Lecturer</a>
        <a href="view_attendance.php">View Attendance Records</a>
        <a href="manage_students.php">Manage Students</a>
        <a href="../logout.php" class="logout">Logout</a>
    </div>

</div>

</body>
</html>