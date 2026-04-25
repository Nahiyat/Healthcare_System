<?php
session_start();

$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
$id   = isset($_GET['id'])   ? htmlspecialchars($_GET['id'])   : '';

// ✅ Set session for new patient too
if ($id != '') {
    $_SESSION['patient_id'] = $id;
}
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

<h2>Registration Successful!</h2>

<p>Welcome <b><?php echo $name; ?></b></p>
<p>Your Patient ID: <b><?php echo $id; ?></b></p>
<p style="color:red;">Please save your Patient ID — you will need it to login.</p>

<br>

<!-- No ?id= needed, session handles it -->
<a href="patient_dashboard.php">
    <button>Go to Dashboard →</button>
</a>

</div>
</div>

</body>
</html>