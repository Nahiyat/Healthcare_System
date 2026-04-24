<?php
$name = $_GET['name'];
$id = $_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Success</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg1">
<div class="overlay">
    <div class="card">

        <h2> Registration Successful</h2>

        <p>Welcome <strong><?php echo $name; ?></strong></p>
        <p>Your Patient ID: <b><?php echo $id; ?></b></p>

        <br>
        <button>Go to Dashboard →</button>

    </div>
</div>

</body>
</html>