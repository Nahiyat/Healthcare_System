<?php
include 'db.php';
session_start();

$id                = $_POST['id'];
$full_name         = $_POST['full_name'];
$date_of_birth     = $_POST['date_of_birth'];
$gender            = $_POST['gender'];
$phone             = $_POST['phone'];
$email             = $_POST['email'];
$address           = $_POST['address'];
$emergency_contact = $_POST['emergency_contact'];
$blood_type        = $_POST['blood_type'];

$stmt = $conn->prepare("UPDATE patients SET 
    full_name         = ?,
    date_of_birth     = ?,
    gender            = ?,
    phone             = ?,
    email             = ?,
    address           = ?,
    emergency_contact = ?,
    blood_type        = ?
    WHERE patient_id  = ?");

$stmt->bind_param(
    "sssssssss",
    $full_name,
    $date_of_birth,
    $gender,
    $phone,
    $email,
    $address,
    $emergency_contact,
    $blood_type,
    $id
);

if ($stmt->execute()) {
    $_SESSION['patient_id'] = $id;
    echo "<div style='text-align:center; padding:40px;'>
            <h3 style='color:green;'>✅ Profile Updated Successfully</h3>
            <a href='patient_dashboard.php'>
                <button>Go to Dashboard</button>
            </a>
          </div>";
} else {
    echo "<div style='text-align:center; padding:40px;'>
            <h3 style='color:red;'>❌ Update Failed: " . htmlspecialchars($conn->error) . "</h3>
          </div>";
}

$stmt->close();
?>