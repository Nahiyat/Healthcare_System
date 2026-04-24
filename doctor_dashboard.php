<?php
include 'db.php';

$id = $_GET['id'];

$sql = "SELECT * FROM doctors WHERE doctor_id='$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg4">

<div class="overlay">

<div class="card" style="width:500px; text-align:left;">

<h2>Welcome Dr. <?php echo $row['name']; ?></h2>
<p><b>ID:</b> <?php echo $row['doctor_id']; ?></p>

<hr>

<h3>Menu</h3>

<ul>
    <li>Appointments</li>
    <li>Medicine List</li>
    <li>Edit Profile</li>
    <li>Your Choices</li>
</ul>

</div>

</div>

</body>
</html>