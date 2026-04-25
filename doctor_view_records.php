<?php
include 'db.php';
session_start();

if (isset($_SESSION['doctor_id'])) {
    $doctor_id = $_SESSION['doctor_id'];
} elseif (isset($_GET['id'])) {
    $doctor_id = $_GET['id'];
    $_SESSION['doctor_id'] = $doctor_id;
} else {
    $doctor_id = '';
}

/* Fetch patients who have submitted a medical record to this doctor */
$patients = null;
if ($doctor_id != '') {
    $stmt = $conn->prepare("
        SELECT mr.record_id, mr.patient_id, p.full_name
        FROM medical_records mr
        INNER JOIN patients p ON mr.patient_id = p.patient_id
        WHERE mr.doctor_id = ?
    ");
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $patients = $stmt->get_result();
    $stmt->close();
}

/* If a patient is selected, fetch their full medical record */
$selected_patient_id = isset($_GET['view']) ? $_GET['view'] : '';
$rec = $sym = $dis = null;

if ($selected_patient_id != '') {

    $stmt = $conn->prepare("SELECT * FROM medical_records WHERE patient_id = ? AND doctor_id = ?");
    $stmt->bind_param("ss", $selected_patient_id, $doctor_id);
    $stmt->execute();
    $rec = $stmt->get_result();
    $stmt->close();

    $stmt = $conn->prepare("
        SELECT s.description
        FROM symptoms s
        INNER JOIN medical_records mr ON s.record_id = mr.record_id
        WHERE mr.patient_id = ? AND mr.doctor_id = ?
    ");
    $stmt->bind_param("ss", $selected_patient_id, $doctor_id);
    $stmt->execute();
    $sym = $stmt->get_result();
    $stmt->close();

    $stmt = $conn->prepare("
        SELECT d.disease_type, d.disease_name, d.description
        FROM diseases d
        INNER JOIN medical_records mr ON d.record_id = mr.record_id
        WHERE mr.patient_id = ? AND mr.doctor_id = ?
    ");
    $stmt->bind_param("ss", $selected_patient_id, $doctor_id);
    $stmt->execute();
    $dis = $stmt->get_result();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical Reports</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            padding: 10px 14px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #00bcd4;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        .view-btn {
            padding: 6px 14px;
            background-color: #00bcd4;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
        }
        .view-btn:hover {
            background-color: #0097a7;
        }
        .record-box {
            margin-top: 25px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e53935;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
        }
        .back-btn:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body class="bg2">

<div class="overlay">
<div class="card" style="width: 700px;">

    <!-- TOP LEFT HEADING -->
    <h2 style="text-align:left;">Medical Reports</h2>
    <hr>

    <!-- PATIENTS TABLE -->
    <?php if ($patients && $patients->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = $patients->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['patient_id']); ?></td>
                    <td><?php echo htmlspecialchars($p['full_name']); ?></td>
                    <td>
                        <a class="view-btn"
                           href="doctor_view_records.php?view=<?php echo urlencode($p['patient_id']); ?>">
                            Show Medical Records
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No patients have submitted medical records yet.</p>
    <?php endif; ?>

    <!-- MEDICAL RECORD VIEW (shows when a patient is selected) -->
    <?php if ($selected_patient_id != ''): ?>
    <div class="record-box">

        <h3>Medical Record — Patient: <?php echo htmlspecialchars($selected_patient_id); ?></h3>
        <hr>

        <h4>Current Problems / Symptoms</h4>
        <?php if ($sym && $sym->num_rows > 0): ?>
            <?php while ($r = $sym->fetch_assoc()): ?>
                <p><?php echo htmlspecialchars($r['description']); ?></p>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No symptoms recorded.</p>
        <?php endif; ?>

        <h4>Previous Medical Issues</h4>

        <h5>Insurance</h5>
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

    </div>
    <?php endif; ?>

    <!-- BACK TO DASHBOARD -->
    <br>
    <a class="back-btn" href="doctor_dashboard.php">Back to Dashboard</a>

</div>
</div>

</body>
</html>