<?php
session_start();
include "../config/db.php";

// Security Gate
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') { 
    header("Location: ../login.php"); 
    exit(); 
}

// Fetch ALL courses to pass them to JavaScript
$all_courses_query = mysqli_query($conn, "SELECT * FROM courses ORDER BY level ASC, course_name ASC");
$courses_by_level = [];
while($row = mysqli_fetch_assoc($all_courses_query)) {
    $courses_by_level[] = $row;
}
$courses_json = json_encode($courses_by_level);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $week = $_POST['week'];
    $date = date("Y-m-d");

    $sql = "INSERT INTO sessions (course_id, week, date) VALUES ('$course_id', '$week', '$date')";
    
    if(mysqli_query($conn, $sql)) {
        header("Location: mark_attendance.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Session</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        // This is the list of courses from your SQL
        const allCourses = <?php echo $courses_json; ?>;

        function filterCourses() {
            const levelSelect = document.getElementById('level_select');
            const courseSelect = document.getElementById('course_select');
            const selectedLevel = levelSelect.value;

            // Clear current options
            courseSelect.innerHTML = '<option value="">-- Select Course --</option>';

            // Filter and add new options
            const filtered = allCourses.filter(c => c.level === selectedLevel);
            
            filtered.forEach(course => {
                let option = document.createElement('option');
                option.value = course.id;
                option.text = course.course_code + " - " + course.course_name;
                courseSelect.appendChild(option);
            });
        }
    </script>
</head>
<body>
<div class="form-card">
    <h2>Create Today's Session</h2>
    
    <form method="POST">
        <label>Select Level:</label>
        <select id="level_select" name="level" onchange="filterCourses()" required>
            <option value="">-- Choose Level --</option>
            <option value="100">100 Level</option>
            <option value="200">200 Level</option>
            <option value="300">300 Level</option>
            <option value="400">400 Level</option>
        </select>

        <label>Select Course:</label>
        <select id="course_select" name="course_id" required>
            <option value="">-- Select Level First --</option>
        </select>

        <label>Select Week:</label>
        <select name="week" required>
            <option value="">-- Select Week --</option>
            <?php for($i = 1; $i <= 15; $i++): ?>
                <option value="Week <?php echo $i; ?>">Week <?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        
        <button type="submit">Start Marking Attendance</button>
    </form>
    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>