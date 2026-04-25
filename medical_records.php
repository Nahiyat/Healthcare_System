<?php
include 'db.php';
session_start();

if (isset($_SESSION['patient_id'])) {
    $patient_id = $_SESSION['patient_id'];
} elseif (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    $_SESSION['patient_id'] = $patient_id;
} else {
    $patient_id = '';
}

$doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : '';

/* -------------------------
   FETCH EXISTING RECORD
   So we can pre-fill the form
------------------------- */
$existing_rec = null;
$existing_sym = null;
$existing_dis = null;

if ($patient_id != '') {

    $stmt = $conn->prepare("SELECT * FROM medical_records WHERE patient_id = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $rec_result = $stmt->get_result();
    $stmt->close();

    if ($rec_result && $rec_result->num_rows > 0) {
        $existing_rec = $rec_result->fetch_assoc();
        $record_id = $existing_rec['record_id'];

        /* Fetch existing symptom */
        $stmt = $conn->prepare("SELECT description FROM symptoms WHERE record_id = ?");
        $stmt->bind_param("i", $record_id);
        $stmt->execute();
        $sym_result = $stmt->get_result();
        $stmt->close();
        if ($sym_result && $sym_result->num_rows > 0) {
            $existing_sym = $sym_result->fetch_assoc();
        }

        /* Fetch existing disease */
        $stmt = $conn->prepare("SELECT * FROM diseases WHERE record_id = ?");
        $stmt->bind_param("i", $record_id);
        $stmt->execute();
        $dis_result = $stmt->get_result();
        $stmt->close();
        if ($dis_result && $dis_result->num_rows > 0) {
            $existing_dis = $dis_result->fetch_assoc();
        }
    }
}

$disease_types = [
    "N/A", "Genetic", "Viral", "Bacterial", "Fungal",
    "Parasitic", "Autoimmune", "Neurological", "Cardiovascular",
    "Respiratory", "Digestive", "Endocrine", "Cancer",
    "Mental Health", "Other"
];
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

<h2><?php echo $existing_rec ? "Update Medical Record" : "Medical Record Form"; ?></h2>

<?php if ($existing_rec): ?>
    <p style="color: #00bcd4;">You already have a record. You can update it below.</p>
<?php endif; ?>

<form action="save_medical_record.php" method="POST">

    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">
    <input type="hidden" name="doctor_id"  value="<?php echo htmlspecialchars($doctor_id); ?>">

    <!-- CURRENT SYMPTOMS -->
    <h4>Current Problems / Symptoms</h4>
    <textarea name="symptom" placeholder="Describe current problems and symptoms..." required>
        <?php echo $existing_sym ? htmlspecialchars($existing_sym['description']) : ''; ?>
    </textarea>

    <!-- DISEASE TYPE -->
    <h4>Previous Disease Type</h4>
    <select name="disease_type" required>
        <option value="">-- Select Type --</option>
        <?php foreach ($disease_types as $type): ?>
            <option value="<?php echo $type; ?>"
                <?php echo ($existing_dis && $existing_dis['disease_type'] == $type) ? "selected" : ""; ?>>
                <?php echo $type; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- DISEASE NAME -->
    <h4>Disease Name</h4>
    <input type="text" name="disease_name"
        value="<?php echo $existing_dis ? htmlspecialchars($existing_dis['disease_name']) : ''; ?>"
        required>

    <!-- DISEASE DESCRIPTION -->
    <h4>Disease Description</h4>
    <textarea name="disease_desc" required>
        <?php echo $existing_dis ? htmlspecialchars($existing_dis['description']) : ''; ?>
    </textarea>

    <!-- INSURANCE -->
    <h4>Insurance</h4>
    <select name="insurance">
        <option value="Yes" <?php echo ($existing_rec && $existing_rec['insurance'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
        <option value="No"  <?php echo ($existing_rec && $existing_rec['insurance'] == 'No')  ? 'selected' : ''; ?>>No</option>
    </select>

    <br><br>
    <button type="submit" name="save">
        <?php echo $existing_rec ? "Update Record" : "Save Record"; ?>
    </button>

</form>

<br>
<a href="patient_dashboard.php">
    <button class="back">Back to Dashboard</button>
</a>

</div>
</div>

</body>
</html>