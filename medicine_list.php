<?php
include 'db.php';
session_start();

if (isset($_SESSION['doctor_id'])) {
    $id = $_SESSION['doctor_id'];
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $_SESSION['doctor_id'] = $id;
} else {
    $id = '';
}

$sql = "SELECT * FROM medicine_list";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medicine List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg3">

<div class="overlay">
<div class="card" style="width:900px;">

<table border="1" width="100%" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Medicine Name</th>
    <th>Description</th>
    <th>Selling price</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
    <td><?php echo htmlspecialchars($row['medicine_id']); ?></td>
    <td><?php echo htmlspecialchars($row['medicine_name']); ?></td>
    <td><?php echo htmlspecialchars($row['description']); ?></td>
    <td><?php echo htmlspecialchars($row['sell_price']); ?></td>

</tr>

<?php } ?>

</table>

</div>
</div>

</body>
</html>