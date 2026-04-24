<?php
include 'db.php';

$name = $_POST['name'];
$spec = $_POST['specialization'];
$phone = $_POST['phone'];
$age = $_POST['age'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

/*  Password match check */
if($password != $confirm) {
    echo "<script>
        alert('Password does not match!');
        window.history.back();
    </script>";
    exit();
}

/* Auto ID */
$doctor_id = "D" . time();

/* Insert */
$sql = "INSERT INTO doctors 
(doctor_id, name, specialization, phone, age, password)
VALUES 
('$doctor_id','$name','$spec','$phone','$age','$password')";

if ($conn->query($sql) === TRUE) {

    header("Location: doctor_login.php?msg=registered&name=$name&id=$doctor_id");
    exit();

} else {
    echo "Error: " . $conn->error;
}
?>