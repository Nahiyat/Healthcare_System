<?php
session_start();
include 'db.php';

if(isset($_POST['login'])) {

    $id   = $_POST['doctor_id'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
    $stmt->bind_param("ss", $id, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        $_SESSION['doctor_id'] = $row['doctor_id'];

        header("Location: doctor_dashboard.php");
        exit();

    } else {
        $error = "Invalid ID or Password";
    }
}
?>

    

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

<!-- ✅ SHOW ERROR -->
<?php if(isset($error)) { ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php } ?>

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









    
    

    


