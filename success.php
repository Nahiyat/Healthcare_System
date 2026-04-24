<?php
$name = $_GET['name'];
$id = $_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Registration Success</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg1">

<div class="overlay">
<div class="card">

<h2>Registration Successful</h2>

<p>Welcome <b><?php echo $name; ?></b></p>
<p>Your Patient ID: <b><?php echo $id; ?></b></p>

<br>

<a href="patient_dashboard.php?id=<?php echo $id; ?>">
<button>Go to Dashboard →</button>
</a>

</div>
</div>

</body>
</html>