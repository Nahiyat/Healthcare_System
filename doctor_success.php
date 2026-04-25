<?php
session_start();

// Block if not logged in
if(!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit();
}

$name = $_SESSION['doctor_name'];
$id   = $_SESSION['doctor_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Success</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg2">

<div class="overlay">
<div class="card" style="text-align:center;">

    <h2>Registration Successful!</h2>
    <p>Welcome, <b>Dr. <?php echo htmlspecialchars($name); ?></b></p>
    <p>Your Doctor ID: <b><?php echo htmlspecialchars($id); ?></b></p>
    <p>Please save your ID for future login.</p>

    <br>

    <a href="doctor_dashboard.php">
        <button>Go to Dashboard</button>
    </a>

</div>
</div>

</body>
</html>

