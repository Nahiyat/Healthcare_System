<?php
include 'db.php';
session_start();

/* ------------------------------
   GET PATIENT ID
--------------------------------*/

// Priority: session → URL → fallback empty
if (isset($_SESSION['patient_id'])) {
    $id = $_SESSION['patient_id'];
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $_SESSION['patient_id'] = $id; // store for next time
} else {
    $id = '';
}

/* ------------------------------
   FETCH PATIENT DATA
--------------------------------*/
$row = null;

if ($id != '') {
    $stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg4">

<div class="overlay">

<div class="card" style="width:450px; text-align:center;">

<!-- ------------------------------
     SAFE DISPLAY (NO ERROR)
-------------------------------- -->

<h2>
<?php
if ($row) {
    echo "Welcome " . htmlspecialchars($row['full_name']);
} else {
    echo "Welcome Guest";
}
?>
</h2>

<p>
<b>Id:</b>
<?php
if ($row) {
    echo htmlspecialchars($row['patient_id']);
} else {
    echo "Not Available";
}
?>
</p>

<hr>

<h3>Dashboard</h3>

<!-- ------------------------------
     BUTTONS (KEEP ID SAFE)
-------------------------------- -->

<a href="doctor_list.php?id=<?php echo $id; ?>">
    <button>Take Appointment</button>
</a>

<a href="edit_patient.php?id=<?php echo $id; ?>">
    <button>Edit Profile</button>
</a>

<a href=" pay_the_bill.php?id=<?php echo $id; ?>">
    <button>Pay Bill </button>
</a>

<a href="medical_records.php?patient_id=<?php echo $id; ?>">
    <button>Medical Records</button>
</a>

<a href="check_appointment.php?id=<?php echo $id; ?>">
    <button>Check Appointment</button>
</a>


<a href="patient_login.php">
    <button class="back">Sign Out</button>
</a>

</div>

</div>

</body>
</html>