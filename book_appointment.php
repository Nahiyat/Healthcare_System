<?php
include 'db.php';

session_start();
/* Get values */
$doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : '';
$patient_id = isset($_SESSION['patient_id']) ? $_GET['patient_id'] : '';

/* Handle submit */
if(isset($_POST['confirm'])) {

    $date = $_POST['date'];
    $doctor_id = $_POST['doctor_id'];
    $patient_id = $_SESSION['patient_id'];

    /* -----------------------------
       SAME DAY BOOKING CHECK
    ----------------------------- */
    $check_sql = "SELECT * FROM appointments 
                  WHERE patient_id='$patient_id' 
                  AND appointment_date='$date'";

    $check_result = $conn->query($check_sql);

    if($check_result->num_rows > 0) {
        echo "<h3 style='color:red; text-align:center;'>
                ❌ You already booked an appointment on this date!
              </h3>";
        echo "<div style='text-align:center;'>
                <a href='doctor_list.php?id=$patient_id'>Go Back</a>
              </div>";
        exit();
    }

    /* Get doctor name */
    $doc_sql = "SELECT name FROM doctors WHERE doctor_id='$doctor_id'";
    $doc_result = $conn->query($doc_sql);
    $doc_row = $doc_result->fetch_assoc();
    $doctor_name = $doc_row['name'];

    /* Insert */
    $sql = "INSERT INTO appointments 
    (patient_id, doctor_id, appointment_date, status)
    VALUES 
    ('$patient_id','$doctor_id','$date','pending')";

    if($conn->query($sql) === TRUE) {

        header("Location: appointment_success.php?doctor_id=" . urlencode($doctor_id) .
               "&doctor_name=" . urlencode($doctor_name) .
               "&date=" . urlencode($date) .
               "&patient_id=" . urlencode($patient_id));
        exit();

    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg2">

<div class="overlay">
<div class="card">

<h2>Confirm Appointment</h2>

<p>Doctor ID: <b><?php echo $doctor_id; ?></b></p>
<p>Doctor ID: <b><?php echo $patient_id; ?></b></p>

<form method="POST">

    <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
    <input type="hidden" name="patient_id" value="<?php echo $_SESSION['patient_id']; ?>">

    <input type="date" name="date" required>

    <button type="submit" name="confirm">Confirm</button>

</form>

</div>
</div>

</body>
</html>