<?php 
include 'db.php';
session_start(); // ← was missing!

if(isset($_POST['login'])){

    $id   = $_POST['patient_id'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT patient_id, password FROM patients WHERE patient_id = ? AND password = ?");
    $stmt->bind_param("ss", $id, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        // ✅ Set session properly
        $_SESSION['patient_id'] = $row['patient_id'];

        $id=$row['patient_id'];
        $_SESSION['patient_id'] = $id;

        header("Location: patient_dashboard.php");
        exit();

    } else {
        $error = "Invalid ID or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg1">

<div class="overlay">
<div class="card">

<h2>Patient Login</h2>

<?php if(isset($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="patient_id" placeholder="Patient ID" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>

<br>
<button disabled>Forgot Password (Coming Soon)</button>

</div>
</div>

</body>
</html>