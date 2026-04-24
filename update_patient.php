<?php
include 'db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$date_of_birth = $_POST['date_of_birth'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$emergency = $_POST['emergency'];
$blood_type = $_POST['blood_type'];

/* UPDATE PATIENT */
$sql = "UPDATE patients SET 
        name='$name',
        date_of_birth='$date_of_birth',
        gender='$gender',
        phone='$phone',
        email='$email',
        address='$address',
        emergency='$emergency',
        blood_type='$blood_type'
        WHERE patient_id='$id'";

if($conn->query($sql) === TRUE) {
    echo "<h3 style='color:green; text-align:center;'>Profile Updated Successfully</h3>";
    echo "<div style='text-align:center;'>
            <a href='patient_dashboard.php?id=$id'>Go to Dashboard</a>
          </div>";
} else {
    echo "Error: " . $conn->error;
}
?>