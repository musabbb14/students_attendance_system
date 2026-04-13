<?php
session_start();
include "../config/db.php";

// Security check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$selected_level = isset($_POST['level']) ? $_POST['level'] : "";
$courses = mysqli_query($conn, "SELECT * FROM courses");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="form-card">
    <h2>View Student Registration Numbers</h2>
    
    <form method="POST">
        <label>Select Level:</label>
        <select name="level" required onchange="this.form.submit()">
            <option value="">-- Select Level --</option>
            <option value="100" <?php if($selected_level == '100') echo 'selected'; ?>>100 Level (UG25/SCCS/)</option>
            <option value="200" <?php if($selected_level == '200') echo 'selected'; ?>>200 Level (UG24/SCCS/)</option>
            <option value="300" <?php if($selected_level == '300') echo 'selected'; ?>>300 Level (UG23/SCCS/)</option>
            <option value="400" <?php if($selected_level == '400') echo 'selected'; ?>>400 Level (UG22/SCCS/)</option>
        </select>
    </form>

    <br>

    <?php if($selected_level != ""): ?>
        <form>
            <label>Registration Number List (1001 - 1100):</label>
            <select name="reg_number_list">
                <?php 
                // Logic to set the correct prefix for the selected level
                $prefix = "";
                if($selected_level == "100") $prefix = "UG25/SCCS/";
                elseif($selected_level == "200") $prefix = "UG24/SCCS/";
                elseif($selected_level == "300") $prefix = "UG23/SCCS/";
                elseif($selected_level == "400") $prefix = "UG22/SCCS/";

                // Auto-generate the sequence from 1001 to 1100
                for($i=1001; $i<=1100; $i++){
                    $full_reg = $prefix . $i;
                    echo "<option value='$full_reg'>$full_reg</option>";
                }
                ?>
            </select>
            
            <p style="margin-top: 15px; font-size: 0.85em; color: #555;">
                * These numbers are automatically used by lecturers in the marking sheet for the <strong><?php echo $selected_level; ?> Level</strong>.
            </p>
        </form>
    <?php endif; ?>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>