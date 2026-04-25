<?php
include 'db.php';
session_start();

if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
} elseif (isset($_GET['id'])) {
    $patient_id = $_GET['id'];
} else {
    $patient_id = '';
}

$row = null;
if ($patient_id != '') {
    $stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    $stmt->close();
}

if (!$row) {
    echo "Patient not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg2">

<div class="overlay">
<div class="card">

<h2>Edit Profile</h2>

<form action="update_patient.php" method="POST">

    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['patient_id']); ?>">

    <label>Full Name</label>
    <input type="text" name="full_name" value="<?php echo htmlspecialchars($row['full_name']); ?>" required>

    <label>Date of Birth</label>
    <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($row['date_of_birth']); ?>" required>

    <label>Gender</label>
    <select name="gender">
        <option <?php if($row['gender']=="Male") echo "selected"; ?>>Male</option>
        <option <?php if($row['gender']=="Female") echo "selected"; ?>>Female</option>
        <option <?php if($row['gender']=="Other") echo "selected"; ?>>Other</option>
    </select>

    <label>Phone</label>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

    <label>Address</label>
    <textarea name="address"><?php echo htmlspecialchars($row['address']); ?></textarea>

    <label>Emergency Contact</label>
    <input type="text" name="emergency_contact" value="<?php echo htmlspecialchars($row['emergency_contact']); ?>">
    
    <label>(leave blank to keep current)</small></label>
    <input type="password" name="password" placeholder="New Password">

    <label>Blood Type</label>
    <select name="blood_type">
        <?php
        $blood_types = ["A+","A-","B+","B-","AB+","AB-","O+","O-"];
        foreach ($blood_types as $bt) {
            $selected = ($row['blood_type'] == $bt) ? "selected" : "";
            echo "<option value='$bt' $selected>$bt</option>";
        }
        ?>
        
    </select>

    <br>
    <button type="submit">Update</button>

</form>

<br>
<a href="patient_dashboard.php">
    <button class="back">Back to Dashboard</button>
</a>

</div>
</div>

</body>
</html>