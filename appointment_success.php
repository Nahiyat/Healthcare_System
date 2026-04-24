<?php
$doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : '';
$doctor_name = isset($_GET['doctor_name']) ? $_GET['doctor_name'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointment Success</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg1">

<div class="overlay">
<div class="card">

<h2>Appointment Request Sent</h2>

<p>
Successfully sent approval booking request to  
<strong>Dr. <?php echo $doctor_name; ?></strong>
</p>

<p><b>Doctor ID:</b> <?php echo $doctor_id; ?></p>
<p><b>Appointment Date:</b> <?php echo $date; ?></p>

<br>

<a href="patient_dashboard.php?id=<?php echo $patient_id; ?>">
    <button>Go to Dashboard →</button>
</a>

</div>
</div>

</body>
</html>