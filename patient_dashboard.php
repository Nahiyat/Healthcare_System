<?php
include 'db.php';

$id = $_GET['id'];

$sql = "SELECT * FROM patients WHERE patient_id='$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg4">

<div class="overlay">

<div class="card" style="width:450px; text-align:center;">

<h2>Welcome <?php echo $row['full_name']; ?></h2>
<p><b>ID:</b> <?php echo $row['patient_id']; ?></p>

<hr>

<h3>Dashboard</h3>

<!-- BUTTONS -->

<a href="doctor_list.php">
    <button>Take Appointment</button>
</a>

<a href="edit_patient.php?id=<?php echo $id; ?>">
    <button>Edit Profile</button>
</a>

<a href="patient_mailbox.php?id=<?php echo $id; ?>">
    <button>Mailbox</button>
</a>

<a href="medical_history.php?id=<?php echo $id; ?>">
    <button>Medical Records</button>
</a>

<a href="patient_login.php">
    <button class="back">Sign Out</button>
</a>

</div>

</div>

</body>
</html>