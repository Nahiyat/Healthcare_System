<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

session_start();

$name = $_POST['name'];
$date_of_birth = $_POST['date_of_birth'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$emergency = $_POST['emergency'];
$blood_type = $_POST['blood_type'];

$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if($password != $confirm){
    die("Password not match");
}

$patient_id = "P" . time() . rand(100,999);

$sql = "INSERT INTO patients 
(patient_id, full_name, date_of_birth, gender, phone, email, address, emergency_contact, blood_type, password)
VALUES 
('$patient_id','$name','$date_of_birth','$gender','$phone','$email','$address','$emergency','$blood_type','$password')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['patient_id']   = $patient_id;
    $_SESSION['name'] = $name;
    header("Location: success.php?name=");
exit();

} else {
    echo "Error: " . $conn->error;
}
?>