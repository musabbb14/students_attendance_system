<?php
session_start();
include "../config/db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $course_id = $_POST['course_id'];
    $week = $_POST['week'];
    $date = date("Y-m-d");

    // 1. Create a session record
    mysqli_query($conn, "INSERT INTO sessions (course_id, week, date) VALUES ('$course_id', '$week', '$date')");
    $session_id = mysqli_insert_id($conn);

    // 2. Loop through each student and save status
    foreach($_POST['status'] as $student_id => $status){
        mysqli_query($conn, "INSERT INTO attendance (student_id, session_id, status) VALUES ('$student_id', '$session_id', '$status')");
    }

    echo "<script>alert('Attendance marked successfully!'); window.location.href='dashboard.php';</script>";
}
?>