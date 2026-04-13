<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') { 
    header("Location: ../login.php"); 
    exit(); 
}

$result = null;
$teacher_name = $_SESSION['name'];

// Fetch only the courses assigned to this specific teacher
$course_list_query = mysqli_query($conn, "SELECT c.* FROM courses c 
    INNER JOIN users u ON c.id = u.course_id 
    WHERE u.name = '$teacher_name' ORDER BY c.course_code ASC");

if(isset($_POST['filter'])){
    $level = mysqli_real_escape_string($conn, $_POST['level']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $week = mysqli_real_escape_string($conn, $_POST['week']);

    $sql = "SELECT a.student_reg, c.course_code, s.date, a.status, s.week
            FROM attendance a
            INNER JOIN sessions s ON a.session_id = s.id
            INNER JOIN courses c ON s.course_id = c.id
            WHERE c.level = '$level' 
            AND s.course_id = '$course_id'
            AND s.week = '$week'
            ORDER BY a.student_reg ASC";

    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher - View Attendance</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="table-container">
    <h2 style="text-align:center; color:#1e3c72;">Attendance Records</h2>

    <div class="form-card" style="width: 100%; margin-bottom: 20px;">
        <form method="POST" style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
            
            <select name="level" required style="width: 150px;">
                <option value="">Level</option>
                <option value="100">100L</option>
                <option value="200">200L</option>
                <option value="300">300L</option>
                <option value="400">400L</option>
            </select>

            <select name="course_id" required style="width: 200px;">
                <option value="">Select Course</option>
                <?php while($c = mysqli_fetch_assoc($course_list_query)): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['course_code']; ?></option>
                <?php endwhile; ?>
            </select>

            <select name="week" required style="width: 150px;">
                <option value="">Week</option>
                <?php for($i=1; $i<=15; $i++) echo "<option value='Week $i'>Week $i</option>"; ?>
            </select>
            
            <button type="submit" name="filter" style="width: 100px;">Filter</button>
        </form>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>Reg Number</th>
                <th>Course</th>
                <th>Week</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if($result && mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $color = ($row['status'] == 'Present') ? 'green' : 'red';
                echo "<tr>
                        <td><strong>".$row['student_reg']."</strong></td>
                        <td>".$row['course_code']."</td>
                        <td>".$row['week']."</td>
                        <td style='color: $color; font-weight: bold;'>".$row['status']."</td>
                        <td>".$row['date']."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>No records found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="back-btn" style="display:block; text-align:center;">← Back</a>
</div>
</body>
</html>