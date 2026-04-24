<?php
include 'db.php';

$name = $_POST['name'];
$dateofbirth = $_POST['dob'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$emergency = $_POST['emergency'];
$blood_type = $_POST['blood_type'];

$patient_id = "P" . time();

$sql = "INSERT INTO patients 
(patient_id, full_name, date_of_birth, gender, phone, email, address, emergency_contact, blood_type)
VALUES 
('$patient_id','$name','$dob','$gender','$phone','$email','$address','$emergency','$blood_type')";

if ($conn->query($sql) === TRUE) {
    header("Location: success.php?name=$name&id=$patient_id");
} else {
    echo "Error: " . $conn->error;
}
?>