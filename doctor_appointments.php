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

/* HANDLE STATUS UPDATE */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['confirm'])) {
        $appointment_id = intval($_POST['appointment_id']);
        $status = "confirmed";
        
        // SECURE - Using prepared statement
        $update_sql = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $status, $appointment_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Appointment confirmed successfully!');</script>";
        } else {
            echo "<script>alert('Error updating appointment!');</script>";
        }
        $stmt->close();
    }
    
    if (isset($_POST['cancel'])) {
        $appointment_id = intval($_POST['appointment_id']);
        $status = "cancelled";
        
        //SECURE - Using prepared statement
        $update_sql = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $status, $appointment_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Appointment cancelled successfully!');</script>";
        } else {
            echo "<script>alert('Error cancelling appointment!');</script>";
        }
        $stmt->close();
    }
}

/* GET DATA */
$sql = "SELECT * FROM appointments WHERE doctor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Appointment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg3">

<div class="overlay">
<div class="card" style="width:900px;">

<table border="1" width="100%" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Date</th>
    <th>Doctor ID</th>
    <th>Patient ID</th>
    <th>Status</th>
    <th>Confirm booking</th>
    <th>Cancel booking</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
    <td><?php echo htmlspecialchars($row['appointment_id']); ?></td>
    <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
    <td><?php echo htmlspecialchars($row['doctor_id']); ?></td>
    <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
    <td>
        <strong><?php echo htmlspecialchars($row['status']); ?></strong>
    </td>
    <td>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
            <button type="submit" name="confirm" 
                    <?php echo ($row['status'] == 'confirmed') ? 'disabled' : ''; ?>>
                Confirm
            </button>
        </form>
    </td>
    <td>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
            <button type="submit" name="cancel"
                    <?php echo ($row['status'] == 'cancelled') ? 'disabled' : ''; ?>>
                Cancel
            </button>
        </form>
    </td>
</tr>

<?php } ?>

</table>

</div>
</div>

</body>
</html>