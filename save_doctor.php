<?php
session_start();
include 'db.php';

$name         = $_POST['name'];
$specialization = $_POST['specialization'];
$phone        = $_POST['phone'];
$age          = $_POST['age'];
$fee          = $_POST['fee'];
$status       = $_POST['status'];
$password     = $_POST['password'];
$confirm      = $_POST['confirm_password'];

if($password !== $confirm) {
    echo "<script>alert('Passwords do not match'); history.back();</script>";
    exit();
}


// Auto-generate doctor ID
$doctor_id = 'DOC' . rand(1000, 9999);

$stmt = $conn->prepare("INSERT INTO doctors (doctor_id, name, specialization, phone, age, fee, status, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $doctor_id, $name, $specialization, $phone, $age, $fee, $status, $password);

if($stmt->execute()) {

    // Set session immediately after registration
    $_SESSION['doctor_id']   = $doctor_id;
    $_SESSION['doctor_name'] = $name;

    header("Location: doctor_success.php");
    exit();

} else {
    echo "Registration failed. Try again.";
}

$stmt->close();
?>