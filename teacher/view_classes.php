<!DOCTYPE html>
<html>
<head>
<title>View Classes</title>
<link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="table-box">

<h2>My Classes</h2>

<table>
<tr>
<th>Course Code</th>
<th>Session Name</th>
<th>Date</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result)){
echo "<tr>
<td>".$row['course_code']."</td>
<td>".$row['session_name']."</td>
<td>".$row['date']."</td>
</tr>";
}
?>

</table>

<br>
<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

</div>

</body>
</html>
