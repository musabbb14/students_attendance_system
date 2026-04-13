<?php
session_start();
include "../config/db.php";
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') { header("Location: ../login.php"); exit(); }

$teacher_id = $_SESSION['user_id'];
$teacher_res = mysqli_query($conn, "SELECT course_id, level FROM users WHERE id = '$teacher_id'");
$teacher_data = mysqli_fetch_assoc($teacher_res);

$course_id = isset($teacher_data['course_id']) ? $teacher_data['course_id'] : 0;

$selected_level = isset($_GET['view_level']) ? mysqli_real_escape_string($conn, $_GET['view_level']) : $teacher_data['level'];

$course_res = mysqli_query($conn, "SELECT course_name, course_code FROM courses WHERE id = '$course_id'");
$course = mysqli_fetch_assoc($course_res);
$display_code = isset($course['course_code']) ? $course['course_code'] : "N/A";
$display_name = isset($course['course_name']) ? $course['course_name'] : "Course Not Assigned";

if(isset($_POST['save'])) {
    $date = date("Y-m-d");
    $week = mysqli_real_escape_string($conn, $_POST['week']);
    
    mysqli_query($conn, "INSERT INTO sessions (course_id, week, date) VALUES ('$course_id', '$week', '$date')");
    $sess_id = mysqli_insert_id($conn);

    foreach($_POST['status'] as $reg_no => $status) {
        $reg_no = mysqli_real_escape_string($conn, $reg_no);
        mysqli_query($conn, "INSERT INTO attendance (student_reg, session_id, status) VALUES ('$reg_no', '$sess_id', '$status')");
    }
    echo "<script>alert('Attendance submitted successfully'); window.location.href='dashboard.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="../css/style.css"></head>
<body>
<div class="table-container">
    <h2>Attendance: <?php echo $display_code; ?> - <?php echo $display_name; ?></h2>
    
    <div class="level-selector-box">
        <form method="GET" action="mark_attendance.php">
            <label><strong>Select Level to Display:</strong></label>
            <select name="view_level" onchange="this.form.submit()" class="bold-select">
                <option value="100" <?php if($selected_level == "100") echo "selected"; ?>>100 Level</option>
                <option value="200" <?php if($selected_level == "200") echo "selected"; ?>>200 Level</option>
                <option value="300" <?php if($selected_level == "300") echo "selected"; ?>>300 Level</option>
                <option value="400" <?php if($selected_level == "400") echo "selected"; ?>>400 Level</option>
            </select>
            <span class="viewing-label">Viewing: <strong><?php echo $selected_level; ?> Level</strong></span>
        </form>
    </div>

    <form method="POST">
        <div class="week-selector-box">
            <label>Lecture Week:</label>
            <select name="week" required class="standard-select">
                <?php for($i=1; $i<=15; $i++) echo "<option value='Week $i'>Week $i</option>"; ?>
            </select>
        </div>

        <table class="attendance-table">
            <thead>
                <tr><th>Reg Number</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php 
                $prefix = "";
                if($selected_level == "100") $prefix = "UG25/SCCS/";
                elseif($selected_level == "200") $prefix = "UG24/SCCS/";
                elseif($selected_level == "300") $prefix = "UG23/SCCS/";
                elseif($selected_level == "400") $prefix = "UG22/SCCS/";

                for($i=1001; $i<=1100; $i++): 
                    $current_reg = $prefix . $i; 
                ?>
                <tr>
                    <td><strong><?php echo $current_reg; ?></strong></td>
                    <td>
                        <select name="status[<?php echo $current_reg; ?>]">
                            <option value="Present">Present</option>
                            <option value="Absent" selected>Absent</option>
                        </select>
                    </td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <button type="submit" name="save" class="submit-btn full-width-btn">Submit & Close Session</button>
    </form>
</div>
</body>
</html>