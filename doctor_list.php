<?php
include 'db.php';

$sql = "SELECT * FROM doctors";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctors List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg4">

<div class="overlay">
<div class="card" style="width:800px; text-align:left;">

<h2>Available Doctors</h2>

<table border="1" width="100%" cellpadding="10" style="border-collapse: collapse;">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Specialization</th>
    <th>Phone</th>
    <th>Age</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
    <td><?php echo $row['doctor_id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['specialization']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['age']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
        <a href="book_appointment.php?doctor_id=<?php echo $row['doctor_id']; ?>">
            <button>Book</button>
        </a>
    </td>
</tr>

<?php } ?>

</table>

</div>
</div>

</body>
</html>