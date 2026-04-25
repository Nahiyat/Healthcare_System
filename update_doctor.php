<?php
include 'db.php';
session_start();

$id             = $_POST['id'];
$name           = $_POST['name'];
$specialization = $_POST['specialization'];
$phone          = $_POST['phone'];
$age            = $_POST['age'];
$fee            = $_POST['fee'];
$status         = $_POST['status'];
$new_password   = $_POST['password'];

/* If password field is filled, update it too — otherwise keep existing */
if (!empty($new_password)) {
    $stmt = $conn->prepare("UPDATE doctors SET
        name            = ?,
        specialization  = ?,
        phone           = ?,
        age             = ?,
        fee             = ?,
        status          = ?,
        password        = ?
        WHERE doctor_id = ?");
    $stmt->bind_param(
        "sssissdss",
        $name,
        $specialization,
        $phone,
        $age,
        $fee,
        $status,
        $new_password,
        $id
    );
} else {
    $stmt = $conn->prepare("UPDATE doctors SET
        name            = ?,
        specialization  = ?,
        phone           = ?,
        age             = ?,
        fee             = ?,
        status          = ?
        WHERE doctor_id = ?");
    $stmt->bind_param(
        "sssisds",
        $name,
        $specialization,
        $phone,
        $age,
        $fee,
        $status,
        $id
    );
}

if ($stmt->execute()) {
    $_SESSION['doctor_id'] = $id;
    echo "<div style='text-align:center; padding:40px;'>
            <h3 style='color:green;'> Profile Updated Successfully</h3>
            <a href='doctor_dashboard.php'>
                <button>Go to Dashboard</button>
            </a>
          </div>";
} else {
    echo "<div style='text-align:center; padding:40px;'>
            <h3 style='color:red;'> Update Failed: " . htmlspecialchars($conn->error) . "</h3>
          </div>";
}

$stmt->close();
?>