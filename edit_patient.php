<?php
include 'db.php';

/* Get patient ID */
$patient_id = $_GET['id'];

/* Fetch patient data */
$sql = "SELECT * FROM patients WHERE patient_id='$patient_id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
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

    <input type="hidden" name="id" value="<?php echo $row['patient_id']; ?>">

    <input type="text" name="name" value="<?php echo $row['name']; ?>" required>

    <input type="date" name="date_of_birth" value="<?php echo $row['date_of_birth']; ?>" required>

    <select name="gender">
        <option <?php if($row['gender']=="Male") echo "selected"; ?>>Male</option>
        <option <?php if($row['gender']=="Female") echo "selected"; ?>>Female</option>
        <option <?php if($row['gender']=="Other") echo "selected"; ?>>Other</option>
    </select>

    <input type="text" name="phone" value="<?php echo $row['phone']; ?>" required>
    <input type="email" name="email" value="<?php echo $row['email']; ?>" required>

    <textarea name="address"><?php echo $row['address']; ?></textarea>

    <input type="text" name="emergency" value="<?php echo $row['emergency']; ?>">

    <select name="blood_type">
        <option <?php if($row['blood_type']=="A+") echo "selected"; ?>>A+</option>
        <option <?php if($row['blood_type']=="A-") echo "selected"; ?>>A-</option>
        <option <?php if($row['blood_type']=="B+") echo "selected"; ?>>B+</option>
        <option <?php if($row['blood_type']=="B-") echo "selected"; ?>>B-</option>
        <option <?php if($row['blood_type']=="AB+") echo "selected"; ?>>AB+</option>
        <option <?php if($row['blood_type']=="AB-") echo "selected"; ?>>AB-</option>
        <option <?php if($row['blood_type']=="O+") echo "selected"; ?>>O+</option>
        <option <?php if($row['blood_type']=="O-") echo "selected"; ?>>O-</option>
    </select>

    <button type="submit">Update</button>

</form>

</div>
</div>

</body>
</html>