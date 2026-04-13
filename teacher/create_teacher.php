<?php
session_start();
include "../config/db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (name, username, password, role)
            VALUES ('$name', '$username', '$password', 'teacher')";

    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Teacher Created Successfully');</script>";
    }else{
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Teacher</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="form-card">
<h2>Create Teacher</h2>

<form method="POST">
<input type="text" name="name" placeholder="Teacher Name" required>
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Create Teacher</button>
</form>

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

</div>

</body>
</html>