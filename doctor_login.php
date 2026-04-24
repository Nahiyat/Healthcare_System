<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg1">

<div class="overlay">
    <div class="card">

<h2>Doctor Login</h2>

<form method="POST">

    <input type="text" name="doctor_id" placeholder="Doctor ID" required>
    <input type="password" name="password" placeholder="Password" required>

    <button type="submit" name="login">Login</button>

</form>

<br>
<button disabled>Forgot Password (Coming Soon)</button>

    </div>
</div>

</body>
</html>

<?php
if(isset($_POST['login'])) {

    $id = $_POST['doctor_id'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM doctors WHERE doctor_id='$id' AND password='$pass'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        echo "<script>
            alert('Welcome back Dr. " . $row['name'] . "');
            window.location='doctor_dashboard.php?id=$id';
        </script>";

    } else {
        echo "<script>alert('Invalid ID or Password');</script>";
    }
}
?>