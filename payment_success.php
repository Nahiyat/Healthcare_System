<?php
// payment_success.php
include 'db.php';
session_start();

if (!isset($_SESSION['patient_id']) || !isset($_GET['bill_id'])) {
    header("Location: view_bills.php");
    exit();
}

$bill_id = intval($_GET['bill_id']);
$transaction_id = $_GET['txn'] ?? 'N/A';

// Get bill details
$sql = "SELECT b.*, p.name AS patient_name 
        FROM bill b
        INNER JOIN Patient p ON b.patient_id = p.patient_id
        WHERE b.bill_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bill_id);
$stmt->execute();
$result = $stmt->get_result();
$bill = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: scaleIn 0.5s ease-in-out;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        h1 {
            color: #28a745;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .receipt {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
            text-align: left;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #ddd;
        }

        .receipt-row:last-child {
            border-bottom: none;
        }

        .receipt-label {
            color: #666;
            font-weight: 600;
        }

        .receipt-value {
            color: #333;
            font-weight: bold;
        }

        .total-amount {
            font-size: 28px;
            color: #667eea;
            margin-top: 10px;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✅</div>
        <h1>Payment Successful!</h1>
        <p class="subtitle">Your payment has been processed successfully</p>

        <div class="receipt">
            <h3 style="margin-bottom: 20px; color: #333;">Payment Receipt</h3>
            
            <div class="receipt-row">
                <span class="receipt-label">Bill ID:</span>
                <span class="receipt-value">#<?php echo $bill['bill_id']; ?></span>
            </div>
            
            <div class="receipt-row">
                <span class="receipt-label">Patient Name:</span>
                <span class="receipt-value"><?php echo htmlspecialchars($bill['patient_name']); ?></span>
            </div>
            
            <div class="receipt-row">
                <span class="receipt-label">Payment Method:</span>
                <span class="receipt-value"><?php echo $bill['payment_method']; ?></span>
            </div>
            
            <div class="receipt-row">
                <span class="receipt-label">Transaction ID:</span>
                <span class="receipt-value"><?php echo $transaction_id; ?></span>
            </div>
            
            <div class="receipt-row">
                <span class="receipt-label">Payment Date:</span>
                <span class="receipt-value">
                    <?php echo date('d M Y, h:i A', strtotime($bill['payment_date'])); ?>
                </span>
            </div>
            
            <div class="receipt-row">
                <span class="receipt-label">Amount Paid:</span>
                <span class="receipt-value total-amount">
                    ₹<?php echo number_format($bill['total_amount'], 2); ?>
                </span>
            </div>
        </div>

        <a href="view_bills.php" class="btn">View All Bills</a>
        <a href="patient_dashboard.php" class="btn btn-secondary">Go to Dashboard</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>