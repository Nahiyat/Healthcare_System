<?php
include 'db.php';
session_start();

if (isset($_SESSION['doctor_id'])) {
    $doctor_id = $_SESSION['doctor_id'];
} elseif (isset($_GET['id'])) {
    $doctor_id = $_GET['id'];
} else {
    $doctor_id = '';
}

$row = null;
if ($doctor_id != '') {
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    $stmt->close();
}

if (!$row) {
    echo "Doctor not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg2">

<div class="overlay">
<div class="card">

<h2>Edit Profile</h2>

<form action="update_doctor.php" method="POST">

    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['doctor_id']); ?>">

    <label>Full Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>

    <label>Specialization</label>
    <select name="specialization" required>
        <?php
        $specs = ["ENT","Cardiology","Neurology","Orthopedic","Cancer","General Physician"];
        foreach ($specs as $s) {
            $selected = ($row['specialization'] == $s) ? "selected" : "";
            echo "<option value='$s' $selected>$s</option>";
        }
        ?>
    </select>

    <label>Phone</label>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>

    <label>Age</label>
    <input type="number" name="age" value="<?php echo htmlspecialchars($row['age']); ?>" required>

    <label>Fee</label>
    <input type="number" name="fee" value="<?php echo htmlspecialchars($row['fee']); ?>" required>

    <label>Status</label>
    <select name="status">
        <option value="available" <?php if($row['status']=="available") echo "selected"; ?>>Available</option>
        <option value="unavailable" <?php if($row['status']=="unavailable") echo "selected"; ?>>Unavailable</option>
    </select>

    <label> (leave blank to keep current)</small></label>
    <input type="password" name="password" placeholder="New Password">

    <br>
    <button type="submit">Update</button>

</form>

<br>
<a href="doctor_dashboard.php">
    <button class="back">Back to Dashboard</button>
</a>

</div>
</div>

</body>
</html>