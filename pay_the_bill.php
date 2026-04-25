<?php
// view_bills_prepared.php
include 'db.php';
session_start();

if (!isset($_SESSION['patient_id'])) {
    die("Please login first");
}

$patient_id = intval($_SESSION['patient_id']);

// ✅ Method 1: Simple query without JOIN
$sql = "SELECT bill_id, appointment_id, total_amount, consultation_fee, 
               medicine_cost, discount_amount, payment_method, payment_date, 
               due_date, created_at
        FROM bill 
        WHERE patient_id = ? 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $patient_id);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();

// Get patient name separately
$patient_sql = "SELECT name FROM Patient WHERE patient_id = ?";
$patient_stmt = $conn->prepare($patient_sql);
$patient_stmt->bind_param("i", $patient_id);
$patient_stmt->execute();
$patient_result = $patient_stmt->get_result();
$patient = $patient_result->fetch_assoc();
$patient_name = $patient ? $patient['name'] : 'Unknown';
$patient_stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bills - <?php echo htmlspecialchars($patient_name); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f0f0f0; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        h2 { color: #333; border-bottom: 3px solid #4CAF50; padding-bottom: 10px; }
        .info { background: #e3f2fd; padding: 15px; margin: 20px 0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #4CAF50; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f5f5f5; }
        .no-bills { text-align: center; padding: 60px; color: #666; }
        .btn { background: #4CAF50; color: white; padding: 10px 20px; 
               text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
               
    </style>
</head>
<body>
    <div class="container">
        <h2>💳 Bills </h2>
        
        <div class="info">
            <strong>Total Bills:</strong> <?php echo $result->num_rows; ?>
        </div>

        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Appt ID</th>
                    <th>Consultation</th>
                    <th>Medicine</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Payment Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php while($bill = $result->fetch_assoc()): ?>
                <tr>
                    <td><strong>#<?php echo $bill['bill_id']; ?></strong></td>
                    <td><?php echo $bill['appointment_id'] ?? 'N/A'; ?></td>
                    <td>Tk.<?php echo number_format($bill['consultation_fee'], 2); ?></td>
                    <td>Tk.<?php echo number_format($bill['medicine_cost'], 2); ?></td>
                    <td>Tk.<?php echo number_format($bill['discount_amount'], 2); ?></td>
                    <td><strong>Tk.<?php echo number_format($bill['total_amount'], 2); ?></strong></td>
                    <td><?php echo $bill['payment_method'] ?? 'Pending'; ?></td>
                    <td>
                        <?php 
                        if ($bill['payment_date']) {
                            echo date('d M Y', strtotime($bill['payment_date']));
                        } else {
                            echo '<span style="color:orange;">Not Paid</span>';
                        }
                        ?>
                    </td>
                    <td><?php echo $bill['due_date'] ? date('d M Y', strtotime($bill['due_date'])) : 'N/A'; ?></td>
                    <td>
                        <?php if ($bill['payment_date']): ?>
                            <span style="color:green; font-weight:bold;">✓ PAID</span>
                        <?php else: ?>
                            <span style="color:orange; font-weight:bold;">⏳ PENDING</span>
                        <?php endif; ?>
                    </td>
    
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        
        <div class="no-bills">
            <h3>📋 No Bills Found</h3>
            <p>You don't have any bills yet.</p>
            <p><small>Patient ID: <?php echo $patient_id; ?></small></p>
            
        </div>
        <?php endif; ?>

                
        <a href="payment.php" class="btn">Payment</a>

        <a href="patient_dashboard.php" class="btn">← Back to Dashboard</a>
    </div>
</body>
</html>


<?php
$stmt->close();
$conn->close();
?>