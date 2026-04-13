<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'student'){
    header("Location: ../login.php");
    exit();
}

$lecturer_query = mysqli_query($conn, "SELECT DISTINCT name FROM users WHERE role IN ('admin', 'teacher') ORDER BY name ASC");

$courses_query = mysqli_query($conn, "SELECT id, course_code, course_name FROM courses ORDER BY course_code ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Portal</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="dashboard-container">
    <h2>Student Attendance Portal</h2>
    <p>Select the details below to view the class attendance record</p>

    <div class="form-card">
        <form action="view_attendance.php" method="GET">
            
            <label>Select Lecturer:</label>
            <select name="lecturer_name" required>
                <option value="">-- Choose Lecturer --</option>
                <?php while($l = mysqli_fetch_assoc($lecturer_query)): ?>
                    <option value="<?php echo $l['name']; ?>"><?php echo $l['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label>Select Level:</label>
            <select name="level" required>
                <option value="">-- Choose Level --</option>
                <option value="100">100 Level</option>
                <option value="200">200 Level</option>
                <option value="300">300 Level</option>
                <option value="400">400 Level</option>
            </select>

            <label>Select Course:</label>
            <select name="course_id" required>
                <option value="">-- Choose Course --</option>
                <?php while($c = mysqli_fetch_assoc($courses_query)): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['course_code'] . " - " . $c['course_name']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">View Course Attendance</button>
        </form>
    </div>

    <div class="menu">
        <a href="../logout.php" class="logout">Logout</a>
    </div>
</div>

</body>
</html>