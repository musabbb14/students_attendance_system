<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$result = null;
$courses = mysqli_query($conn, "SELECT * FROM courses ORDER BY course_code ASC");

$saved_level = isset($_POST['level']) ? $_POST['level'] : (isset($_GET['lvl']) ? $_GET['lvl'] : '');
$saved_course = isset($_POST['course_id']) ? $_POST['course_id'] : (isset($_GET['crs']) ? $_GET['crs'] : '');
$saved_lecturer = isset($_POST['lecturer_name']) ? $_POST['lecturer_name'] : (isset($_GET['lec']) ? $_GET['lec'] : '');

if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM attendance WHERE id = '$id'");

    header("Location: manage_students.php?msg=removed&lvl=$saved_level&crs=$saved_course&lec=$saved_lecturer");
    exit();
}

if (isset($_POST['add_student'])) {
    $sid = mysqli_real_escape_string($conn, $_POST['session_id']);
    $reg = mysqli_real_escape_string($conn, $_POST['reg_no']);
    $lvl = mysqli_real_escape_string($conn, $_POST['level']);
    $crs = mysqli_real_escape_string($conn, $_POST['course_id']);

    if(!empty($sid)) {
        
        mysqli_query($conn, "INSERT INTO attendance (session_id, student_reg, status) VALUES ('$sid', '$reg', 'Present')");

        $check_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$reg'");
        if(mysqli_num_rows($check_user) == 0) {
            mysqli_query($conn, "INSERT INTO users (name, username, password, role, level, course_id) 
                                VALUES ('$reg', '$reg', '$reg', 'student', '$lvl', '$crs')");
        }
        
        echo "<script>alert('Student added to System and Attendance successfully');</script>";
    }
}

if (isset($_POST['show_list']) || isset($_GET['lvl'])) {
    $level = mysqli_real_escape_string($conn, $saved_level);
    $course_id = mysqli_real_escape_string($conn, $saved_course);
    $lecturer = mysqli_real_escape_string($conn, $saved_lecturer);

    $sql = "SELECT a.id, a.student_reg, s.id as session_id 
            FROM attendance a 
            JOIN sessions s ON a.session_id = s.id 
            JOIN users u ON s.course_id = u.course_id
            WHERE s.course_id = '$course_id' 
            AND u.name = '$lecturer' 
            AND u.level = '$level'";
    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="table-container">
    <h2>Manage Student Attendance</h2>
    
    <form method="POST" class="admin-filter-form">
        <select name="level" required>
            <option value="">-- Select Level --</option>
            <option value="100" <?php if($saved_level == '100') echo 'selected'; ?>>100 Level</option>
            <option value="200" <?php if($saved_level == '200') echo 'selected'; ?>>200 Level</option>
            <option value="300" <?php if($saved_level == '300') echo 'selected'; ?>>300 Level</option>
            <option value="400" <?php if($saved_level == '400') echo 'selected'; ?>>400 Level</option>
        </select>

        <select name="course_id" required>
            <option value="">-- Select Course --</option>
            <?php 
            mysqli_data_seek($courses, 0);
            while($c = mysqli_fetch_assoc($courses)): 
            ?>
                <option value="<?php echo $c['id']; ?>" <?php if($saved_course == $c['id']) echo 'selected'; ?>>
                    <?php echo $c['course_code']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="lecturer_name" required>
            <option value="">-- Select Lecturer --</option>
            <?php 
            $lecturers = ["Dr. Ali Aminu", "Dr. Ahmed Muhammed Kabir", "Dr. Bala Modi", "Dr. Suleiman Salihu Jauro", "Mal. Lamido Yahaya", "Mal. Maimuna Salisu Tabra", "Mal. Ahmad Jibir Kawu", "Mal. Abubakar Adamu", "Mr. Adamu Hussaini", "Mr. Aliyuda Ali", "Mr. Muhammad Saleh Bute", "Mal. Hajara Musa", "Mr. Aliyu Abubakar", "Mr. Muhammad Dawaki"];
            foreach($lecturers as $lec):
            ?>
                <option value="<?php echo $lec; ?>" <?php if($saved_lecturer == $lec) echo 'selected'; ?>><?php echo $lec; ?></option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit" name="show_list">Edit Attendance</button>
    </form>

    <hr>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>Registration Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $current_session_id = "";
            while ($row = mysqli_fetch_assoc($result)): 
                $current_session_id = $row['session_id']; 
            ?>
                <tr>
                    <td><strong><?php echo $row['student_reg']; ?></strong></td>
                    <td>
                        <a href="manage_students.php?delete_id=<?php echo $row['id']; ?>&lvl=<?php echo $saved_level; ?>&crs=<?php echo $saved_course; ?>&lec=<?php echo $saved_lecturer; ?>" 
                           onclick="return confirm('Are you sure you want to remove this student?')" 
                           class="delete-link">Remove Student</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <div class="admin-actions">
            <h3>Add New Student to this Class</h3>
            <form method="POST">
                <input type="hidden" name="session_id" value="<?php echo $current_session_id; ?>">
                <input type="hidden" name="level" value="<?php echo $saved_level; ?>">
                <input type="hidden" name="course_id" value="<?php echo $saved_course; ?>">
                <input type="hidden" name="lecturer_name" value="<?php echo $saved_lecturer; ?>">
                
                <input type="text" name="reg_no" placeholder="Enter Registration Number" required class="admin-input">
                <button type="submit" name="add_student" class="add-btn">Add Student</button>
            </form>
        </div>
    <?php elseif(isset($_POST['show_list']) || isset($_GET['lvl'])): ?>
        <p class="error-msg">No records found for this lecturer in this course/level.</p>
    <?php endif; ?>
    
    <br>
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>
</body>
</html>