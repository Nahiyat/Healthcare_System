<?php
include 'db.php';
session_start();

if (isset($_SESSION['doctor_id'])) {
    $id = $_SESSION['doctor_id'];
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $_SESSION['doctor_id'] = $id;
} else {
    $id = '';
}

$row = null;
if ($id != '') {
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .menu-btn {
            display: block;
            width: 95%;
            padding: 12px;
            margin: 8px 0;
            background-color: #00bcd4;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        .menu-btn:hover {
            background-color: #0097a7;
        }
        .menu-btn.back {
            background-color: #e53935;
        }
        .menu-btn.back:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body class="bg4">

<div class="overlay">
<div class="card" style="width:500px; text-align:center;">

<h2>
<?php
if ($row) {
    echo "Welcome Dr. " . htmlspecialchars($row['name']);
} else {
    echo "Welcome Doctor";
}
?>
</h2>

<p><b>ID:</b> <?php echo $row ? htmlspecialchars($row['doctor_id']) : "Not Available"; ?></p>
<p><b>Specialization:</b> <?php echo $row ? htmlspecialchars($row['specialization']) : "-"; ?></p>

<hr>
<h3>Menu</h3>

<a class="menu-btn" href="doctor_appointments.php?id=<?php echo htmlspecialchars($id); ?>">
    See Appointments
</a>

<a class="menu-btn" href="medicine_list.php?id=<?php echo htmlspecialchars($id); ?>">
    Medicine List
</a>

<a class="menu-btn" href="edit_doctor.php?id=<?php echo htmlspecialchars($id); ?>">
    Edit Profile
</a>

<a class="menu-btn" href="doctor_view_records.php?id=<?php echo htmlspecialchars($id); ?>">
    View Patient Medical Report
</a>

<a class="menu-btn" href="prescribe.php?id=<?php echo htmlspecialchars($id); ?>">
    Prescribe to Patients
</a>


<a class="menu-btn back" href="doctor_login.php">
    Sign Out
</a>

</div>
</div>

</body>
</html>