<?php
session_start();
include "../config/db.php";

// 1. Security check
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'student'){
    header("Location: ../login.php");
    exit();
}

$result = null;

// 2. Fetch courses only
$courses_query = mysqli_query($conn, "SELECT id, course_code, course_name FROM courses ORDER BY course_code ASC");

// 3. Handle the filter logic
if(isset($_GET['course_id']) && isset($_GET['level']) && isset($_GET['lecturer_name'])){
    $course_id = mysqli_real_escape_string($conn, $_GET['course_id']);
    $level = mysqli_real_escape_string($conn, $_GET['level']);
    $lecturer_name = mysqli_real_escape_string($conn, $_GET['lecturer_name']);
    $week = mysqli_real_escape_string($conn, $_GET['week']); 
    $status_filter = mysqli_real_escape_string($conn, $_GET['status_filter']); 

    // Query to show records for the selected class/lecturer
    $sql = "SELECT a.student_reg, a.status, s.week, s.date, c.course_code
            FROM attendance a
            INNER JOIN sessions s ON a.session_id = s.id
            INNER JOIN courses c ON s.course_id = c.id
            INNER JOIN users u ON c.id = u.course_id
            WHERE c.id = '$course_id' 
            AND c.level = '$level'
            AND u.name = '$lecturer_name'";

    if($week !== "all") {
        $sql .= " AND s.week = '$week'";
    }

    if($status_filter !== "all") {
        $sql .= " AND a.status = '$status_filter'";
    }

    $sql .= " ORDER BY s.date DESC, a.student_reg ASC";
    $result = mysqli_query($conn, $sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student - Attendance Records</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="table-container">
    <h2 class="student-view-title">Course Attendance Records</h2>

    <div class="form-card student-filter-card">
        <form method="GET" action="view_attendance.php" class="student-filter-form">
            
            <select name="lecturer_name" required>
                <option value="">-- Lecturer --</option>
                <?php
                $lecturers = [
                    "Dr. Ali Aminu", "Dr. Ahmed Muhammed Kabir", "Dr. Bala Modi", 
                    "Dr. Suleiman Salihu Jauro", "Mal. Lamido Yahaya", "Mal. Maimuna Salisu Tabra", 
                    "Mal. Ahmad Jibir Kawu", "Mal. Abubakar Adamu", "Mr. Adamu Hussaini", 
                    "Mr. Mamuda Friday", "Mr. Muhammad Saleh Bute", "Mal. Hajara Musa", 
                    "Mr. Aliyu Abubakar", "Mr. Muhammad Dawaki"
                ];
                foreach($lecturers as $name):
                    $selected = (isset($_GET['lecturer_name']) && $_GET['lecturer_name'] == $name) ? 'selected' : '';
                ?>
                    <option value="<?php echo $name; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>

            <select name="level" required>
                <option value="">-- Level --</option>
                <?php foreach(['100', '200', '300', '400'] as $lv): ?>
                    <option value="<?php echo $lv; ?>" <?php echo (isset($_GET['level']) && $_GET['level'] == $lv) ? 'selected' : ''; ?>>
                        <?php echo $lv; ?> Level
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="course_id" required>
                <option value="">-- Course --</option>
                <?php 
                mysqli_data_seek($courses_query, 0); 
                while($c = mysqli_fetch_assoc($courses_query)): 
                ?>
                    <option value="<?php echo $c['id']; ?>" <?php echo (isset($_GET['course_id']) && $_GET['course_id'] == $c['id']) ? 'selected' : ''; ?>>
                        <?php echo $c['course_code']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="week" required>
                <option value="all" <?php echo (isset($_GET['week']) && $_GET['week'] == 'all') ? 'selected' : ''; ?>>All Weeks</option>
                <?php for($i=1; $i<=15; $i++): 
                    $w = "Week $i";
                    $selected = (isset($_GET['week']) && $_GET['week'] == $w) ? 'selected' : '';
                ?>
                    <option value="<?php echo $w; ?>" <?php echo $selected; ?>>Week <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>

            <select name="status_filter" required>
                <option value="all" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'all') ? 'selected' : ''; ?>>All Statuses</option>
                <option value="Present" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Present') ? 'selected' : ''; ?>>Present Only</option>
                <option value="Absent" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Absent') ? 'selected' : ''; ?>>Absent Only</option>
            </select>

            <button type="submit">View Record</button>
        </form>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>Registration Number</th>
                <th>Course</th> <th>Week</th>
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
                        <td>".$row['course_code']."</td> <td>".$row['week']."</td>
                        <td class='$status_class'>".$row['status']."</td>
                        <td>".$row['date']."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='no-records-cell'>No records found. Select filters above to search.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <br>
    <a href="../logout.php" class="student-logout-btn">Logout</a>
</div>

</body>
</html>