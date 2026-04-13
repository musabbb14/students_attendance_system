<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher'){
    header("Location: ../login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

$courses_query = mysqli_query($conn, "SELECT id, course_code FROM courses ORDER BY course_code ASC");

$selected_course = isset($_GET['course_id']) ? $_GET['course_id'] : '';
$selected_level = isset($_GET['level']) ? $_GET['level'] : '';
$selected_week = isset($_GET['week']) ? $_GET['week'] : '';
$selected_status = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

$records = null;

if (!empty($selected_course) && !empty($selected_level) && !empty($selected_week)) {
    $record_query = "SELECT a.student_reg, a.status, s.date, s.week, c.course_code 
                     FROM attendance a 
                     JOIN sessions s ON a.session_id = s.id 
                     JOIN courses c ON s.course_id = c.id
                     WHERE s.course_id = '$selected_course' 
                     AND s.week = '$selected_week'"; 

    if ($selected_status !== '' && $selected_status !== 'all') {
        $record_query .= " AND a.status = '$selected_status'";
    }

    $record_query .= " ORDER BY a.student_reg ASC";
    $records = mysqli_query($conn, $record_query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Attendance Records</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="table-container">
    <h2>Attendance Records</h2>
    
    <div class="filter-box-container">
        <form method="GET" class="horizontal-filter-form">
            
            <label><strong>Course:</strong></label>
            <select name="course_id" class="padded-select" required>
                <option value="">-- Select Course --</option>
                <?php 
                mysqli_data_seek($courses_query, 0);
                while($c = mysqli_fetch_assoc($courses_query)): 
                ?>
                    <option value="<?php echo $c['id']; ?>" <?php if($selected_course == $c['id']) echo 'selected'; ?>>
                        <?php echo $c['course_code']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label><strong>Level:</strong></label>
            <select name="level" class="padded-select" required>
                <option value="">-- Select Level --</option>
                <option value="100" <?php if($selected_level == '100') echo 'selected'; ?>>100 Level</option>
                <option value="200" <?php if($selected_level == '200') echo 'selected'; ?>>200 Level</option>
                <option value="300" <?php if($selected_level == '300') echo 'selected'; ?>>300 Level</option>
                <option value="400" <?php if($selected_level == '400') echo 'selected'; ?>>400 Level</option>
            </select>

            <label><strong>Week:</strong></label>
            <select name="week" class="padded-select" required>
                <option value="">-- Select Week --</option>
                <?php for($i=1; $i<=15; $i++): $w = "Week $i"; ?>
                    <option value="<?php echo $w; ?>" <?php if($selected_week == $w) echo 'selected'; ?>><?php echo $w; ?></option>
                <?php endfor; ?>
            </select>

            <label><strong>Status:</strong></label>
            <select name="status_filter" class="padded-select">
                <option value="">-- Select Status --</option>
                <option value="all" <?php if($selected_status == 'all') echo 'selected'; ?>>All Students</option>
                <option value="Present" <?php if($selected_status == 'Present') echo 'selected'; ?>>Present Only</option>
                <option value="Absent" <?php if($selected_status == 'Absent') echo 'selected'; ?>>Absent Only</option>
            </select>
            
            <button type="submit" class="filter-btn">Filter Records</button>
        </form>
    </div>

    <?php if (!empty($selected_course) && !empty($selected_level) && !empty($selected_week)): ?>
    <table class="attendance-table">
        <thead>
            <tr>
                <th>Student Reg Number</th>
                <th>Course</th>
                <th>Week</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if($records && mysqli_num_rows($records) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($records)): ?>
                <tr>
                    <td><strong><?php echo $row['student_reg']; ?></strong></td>
                    <td><?php echo $row['course_code']; ?></td>
                    <td><?php echo $row['week']; ?></td>
                    <td class="<?php echo ($row['status'] == 'Present') ? 'status-present' : 'status-absent'; ?>">
                        <?php echo $row['status']; ?>
                    </td>
                    <td><?php echo $row['date']; ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="no-records-cell">No records found for the selected criteria.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p style="text-align: center; margin-top: 20px; color: #555;">Please select a Course, Level, and Week to view records.</p>
    <?php endif; ?>

    <div class="footer-actions">
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>