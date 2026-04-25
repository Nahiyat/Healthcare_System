<?php
include 'db.php';

/* GET DATA */
$sql = "SELECT * FROM appointments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Appointment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg3">

<div class="overlay">
<div class="card" style="width:900px;">

<table border="1" width="100%" cellpadding="10">

<tr>
    <th>Date</th>
    <th>Doctor ID</th>
    <th>Status</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
    <td><?php echo $row['appointment_date']; ?></td>
    <td><?php echo $row['doctor_id']; ?></td>

    <td>
        <?php echo $row['status']; ?>
    </td>
</tr>

<?php } ?>

</table>

</div>
</div>

</body>
</html>