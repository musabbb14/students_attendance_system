<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit(); 
}

$result = null;

$course_list_query = mysqli_query($conn, "SELECT * FROM courses ORDER BY course_code ASC");

if(isset($_POST['filter'])){
    $level = mysqli_real_escape_string($conn, $_POST['level']);
    $week  = mysqli_real_escape_string($conn, $_POST['week']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $lecturer = mysqli_real_escape_string($conn, $_POST['lecturer_name']);
    $status_filter = mysqli_real_escape_string($conn, $_POST['status_filter']); 

    $sql = "SELECT attendance.student_reg, courses.course_code, sessions.date, attendance.status, sessions.week
            FROM attendance
            INNER JOIN sessions ON attendance.session_id = sessions.id
            INNER JOIN courses ON sessions.course_id = courses.id
            INNER JOIN users ON courses.id = users.course_id
            WHERE courses.level = '$level' 
            AND sessions.week = '$week' 
            AND sessions.course_id = '$course_id'
            AND users.name = '$lecturer'";

    if ($status_filter !== "all") {
        $sql .= " AND attendance.status = '$status_filter'";
    }

    $sql .= " ORDER BY attendance.student_reg ASC";

    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Attendance - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="table-container">
    <h2 class="admin-header">Admin: Attendance Records</h2>

    <form method="POST" class="filter-form">
        <select name="level" required>
            <option value="">Select Level</option>
            <option value="100">100 Level</option>
            <option value="200">200 Level</option>
            <option value="300">300 Level</option>
            <option value="400">400 Level</option>
        </select>

        <select name="course_id" required>
            <option value="">Select Course</option>
            <?php while($c = mysqli_fetch_assoc($course_list_query)): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo $c['course_code']; ?></option>
            <?php endwhile; ?>
        </select>

        <select name="lecturer_name" required>
            <option value="">Select Lecturer</option>
            <option value="Dr. Ali Aminu">Dr. Ali Aminu</option>
            <option value="Dr. Ahmed Muhammed Kabir">Dr. Ahmed Muhammed Kabir</option>
            <option value="Dr. Bala Modi">Dr. Bala Modi</option>
            <option value="Dr. Suleiman Salihu Jauro">Dr. Suleiman Salihu Jauro</option>
            <option value="Mal. Lamido Yahaya">Mal. Lamido Yahaya</option>
            <option value="Mal. Maimuna Salisu Tabra">Mal. Maimuna Salisu Tabra</option>
            <option value="Mal. Ahmad Jibir Kawu">Mal. Ahmad Jibir Kawu</option>
            <option value="Mal. Abubakar Adamu">Mal. Abubakar Adamu</option>
            <option value="Mr. Adamu Hussaini">Mr. Adamu Hussaini</option>
            <option value="Mr. Aliyuda Ali">Mr. Aliyuda Ali</option>
            <option value="Mr. Muhammad Saleh Bute">Mr. Muhammad Saleh Bute</option>
            <option value="Mal. Hajara Musa">Mal. Hajara Musa</option>
            <option value="Mr. Aliyu Abubakar">Mr. Aliyu Abubakar</option>
            <option value="Mr. Muhammad Dawaki">Mr. Muhammad Dawaki</option>
        </select>

        <select name="week" required>
            <option value="">Select Week</option>
            <?php for($i=1; $i<=15; $i++) echo "<option value='Week $i'>Week $i</option>"; ?>
        </select>

        <select name="status_filter" required>
            <option value="all">All Students</option>
            <option value="Present">Present Only</option>
            <option value="Absent">Absent Only</option>
        </select>
        
        <button type="submit" name="filter">Search Records</button>
    </form>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>Registration Number</th>
                <th>Course Code</th>
                <th>Week</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if($result && mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $status_class = ($row['status'] == 'Present') ? 'status-present' : 'status-absent';
                echo "<tr>
                        <td><strong>".$row['student_reg']."</strong></td>
                        <td>".$row['course_code']."</td>
                        <td>".$row['week']."</td>
                        <td class='$status_class'>".$row['status']."</td>
                        <td>".$row['date']."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='no-records'>No records found for this selection.</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <div class="back-btn-container">
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>