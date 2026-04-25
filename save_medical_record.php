<?php
include 'db.php';
session_start();

// Get patient_id
if (isset($_POST['patient_id']) && $_POST['patient_id'] != '') {
    $patient_id = $_POST['patient_id'];
    $_SESSION['patient_id'] = $patient_id;
} elseif (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
} else {
    $patient_id = '';
}

$doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : '';
$save_success = false;

/* -------------------------
   SAVE DATA
------------------------- */
if (isset($_POST['save']) && $patient_id != '') {

    $symptom      = $_POST['symptom'];
    $disease_type = $_POST['disease_type'];
    $disease_name = $_POST['disease_name'];
    $disease_desc = $_POST['disease_desc'];
    $insurance    = $_POST['insurance'];

    /* Check if a medical record already exists for this patient */
    $stmt = $conn->prepare("SELECT record_id FROM medical_records WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $existing = $stmt->get_result();
    $stmt->close();

    if ($existing->num_rows > 0) {
        /* --- RECORD EXISTS: UPDATE it --- */
        $row = $existing->fetch_assoc();
        $record_id = $row['record_id'];

        /* Update medical_records */
        $stmt = $conn->prepare("UPDATE medical_records SET doctor_id = ?, insurance = ? WHERE record_id = ?");
        $stmt->bind_param("ssi", $doctor_id, $insurance, $record_id);
        $stmt->execute();
        $stmt->close();

        /* Update symptom */
        $stmt = $conn->prepare("UPDATE symptoms SET description = ? WHERE record_id = ?");
        $stmt->bind_param("si", $symptom, $record_id);
        $stmt->execute();
        $stmt->close();

        /* Update disease */
        $stmt = $conn->prepare("UPDATE diseases SET disease_type = ?, disease_name = ?, description = ? WHERE record_id = ?");
        $stmt->bind_param("sssi", $disease_type, $disease_name, $disease_desc, $record_id);
        $stmt->execute();
        $stmt->close();

    } else {
        /* --- NO RECORD: INSERT new --- */

        /* Step 1: Insert medical record */
        $stmt = $conn->prepare("INSERT INTO medical_records (patient_id, doctor_id, insurance) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $patient_id, $doctor_id, $insurance);
        $stmt->execute();
        $record_id = $conn->insert_id;
        $stmt->close();

        /* Step 2: Insert symptom */
        $stmt = $conn->prepare("INSERT INTO symptoms (record_id, description) VALUES (?, ?)");
        $stmt->bind_param("is", $record_id, $symptom);
        $stmt->execute();
        $stmt->close();

        /* Step 3: Insert disease */
        $stmt = $conn->prepare("INSERT INTO diseases (record_id, disease_type, disease_name, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $record_id, $disease_type, $disease_name, $disease_desc);
        $stmt->execute();
        $stmt->close();
    }

    $save_success = true;
}

/* -------------------------
   FETCH DATA
------------------------- */
$rec = $sym = $dis = null;

if ($patient_id != '') {

    $stmt = $conn->prepare("SELECT * FROM medical_records WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $rec = $stmt->get_result();
    $stmt->close();

    $stmt = $conn->prepare("
        SELECT s.description
        FROM symptoms s
        INNER JOIN medical_records mr ON s.record_id = mr.record_id
        WHERE mr.patient_id = ?
    ");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $sym = $stmt->get_result();
    $stmt->close();

    $stmt = $conn->prepare("
        SELECT d.disease_type, d.disease_name, d.description
        FROM diseases d
        INNER JOIN medical_records mr ON d.record_id = mr.record_id
        WHERE mr.patient_id = ?
    ");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $dis = $stmt->get_result();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical Records</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg2">

<div class="overlay">
<div class="card">

<?php if ($save_success): ?>

    <!-- SUCCESS MESSAGE -->
    <h2> Record Submitted</h2>
    <p>Your medical record has been saved successfully.</p>
    <p>Your doctor will review it shortly.</p>
    <br>

    <hr>

    <!-- SHOW WHAT WAS SAVED -->
    <h3>Current Problems / Symptoms</h3>
    <?php if ($sym && $sym->num_rows > 0): ?>
        <?php while ($r = $sym->fetch_assoc()): ?>
            <p><?php echo htmlspecialchars($r['description']); ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No symptoms recorded.</p>
    <?php endif; ?>

    <h4>Previous Medical Issues:</h4>

    <h5>Medical Info</h5>
    <?php if ($rec && $rec->num_rows > 0): ?>
        <?php while ($r = $rec->fetch_assoc()): ?>
            <p>Insurance: <?php echo htmlspecialchars($r['insurance']); ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No records found.</p>
    <?php endif; ?>

    <h5>Diseases</h5>
    <?php if ($dis && $dis->num_rows > 0): ?>
        <?php while ($r = $dis->fetch_assoc()): ?>
            <p>
                <?php echo htmlspecialchars($r['disease_type']); ?> -
                <?php echo htmlspecialchars($r['disease_name']); ?> :
                <?php echo htmlspecialchars($r['description']); ?>
            </p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No diseases recorded.</p>
    <?php endif; ?>

<?php else: ?>

    <h2>Medical Records</h2>
    <hr>

    <h3>Current Problems / Symptoms</h3>
    <?php if ($sym && $sym->num_rows > 0): ?>
        <?php while ($r = $sym->fetch_assoc()): ?>
            <p><?php echo htmlspecialchars($r['description']); ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No symptoms recorded.</p>
    <?php endif; ?>

    <h4>Previous Medical Issues:</h4>

    <h5>Medical Info</h5>
    <?php if ($rec && $rec->num_rows > 0): ?>
        <?php while ($r = $rec->fetch_assoc()): ?>
            <p>Insurance: <?php echo htmlspecialchars($r['insurance']); ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No records found.</p>
    <?php endif; ?>

    <h5>Diseases</h5>
    <?php if ($dis && $dis->num_rows > 0): ?>
        <?php while ($r = $dis->fetch_assoc()): ?>
            <p>
                <?php echo htmlspecialchars($r['disease_type']); ?> -
                <?php echo htmlspecialchars($r['disease_name']); ?> :
                <?php echo htmlspecialchars($r['description']); ?>
            </p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No diseases recorded.</p>
    <?php endif; ?>

<?php endif; ?>

<br>
<a href="patient_dashboard.php">
    <button>Back to Dashboard</button>
</a>

</div>
</div>

</body>
</html>