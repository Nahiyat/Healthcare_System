<?php
$name = $_GET['name'];
$id = $_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Registration Success</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg1">

<div class="overlay">
    <div class="card">

        <h2>Registration Successful</h2>

        <p>Welcome <strong>Dr. <?php echo $name; ?></strong></p>

        <p>Your Doctor ID: <b><?php echo $id; ?></b></p>

        <br>

        <a href="doctor_dashboard.php?id=<?php echo $id; ?>">
            <button>Go to Dashboard</button>
        </a>

    </div>
</div>

</body>
</html>


