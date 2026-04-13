<?php
session_start();
include "../config/db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){
   
    $name = mysqli_real_escape_string($conn, $_POST['name']); 
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $course_id = mysqli_real_escape_string($conn, $_POST['course_id']);
    $level = mysqli_real_escape_string($conn, $_POST['level']);

    $sql = "INSERT INTO users (name, username, password, role, course_id, level)
            VALUES ('$name', '$username', '$password', 'teacher', '$course_id', '$level')";

    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Teacher Account Linked Successfully'); window.location.href='dashboard.php';</script>";
    }
}
$courses = mysqli_query($conn, "SELECT * FROM courses");
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="../css/style.css"></head>
<body>
<div class="form-card">
    <h2>Assign Lecturer to Course</h2>
    <form method="POST">
        
        <select name="name" required>
            <option value="">-- Select Lecturer Name --</option>
            <option value="Dr. Ali Aminu">Dr. Ali Aminu</option>
            <option value="Dr. Ahmed Muhammed Kabir">Dr. Ahmed Muhammed Kabir</option>
            <option value="Dr. Bala Modi">Dr. Bala Modi</option>
            <option value="Dr. Suleiman Salihu Jauro">Dr. Suleiman Salihu Jauro</option>
            <option value="Mal. Lamido Yahaya">Mal. Lamido Yahaya</option>
            <option value="Mal. Maimuna Salisu Tabra">Mal. Maimuna Salisu Tabra</option>
            <option value="Mal. Ahmad Jibir Kawu">Mal. Ahmad Jibir Kawu</option>
            <option value="Mal. Abubakar Adamu">Mal. Abubakar Adamu</option>
            <option value="Mr. Adamu Hussaini">Mr. Adamu Hussaini</option>
            <option value="Mr. Mamuda Friday">Mr. Mamuda Friday</option>
            <option value="Mr. Muhammad Saleh Bute">Mr. Muhammad Saleh Bute</option>
            <option value="Mal. Hajara Musa">Mal. Hajara Musa</option>
            <option value="Mr. Aliyu Abubakar">Mr. Aliyu Abubakar</option>
            <option value="Mr. Muhammad Dawaki">Mr. Muhammad Dawaki</option>
        </select>

        <input type="text" name="username" placeholder="Login Username" required>
        <input type="password" name="password" placeholder="Login Password" required>
        
        <select name="level" required>
            <option value="">-- Select Level --</option>
            <option value="100">100 Level</option>
            <option value="200">200 Level</option>
            <option value="300">300 Level</option>
            <option value="400">400 Level</option>
        </select>

        <select name="course_id" required>
            <option value="">-- Assign to Course --</option>
            <?php while($c = mysqli_fetch_assoc($courses)): ?>
                <option value="<?php echo $c['id']; ?>"><?php echo $c['course_code']; ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Assign Lecturer</button>
    </form>
    <a href="dashboard.php" class="back-btn">Back</a>
</div>
</body>
</html>