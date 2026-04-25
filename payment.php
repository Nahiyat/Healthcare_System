<?php
// pay_bill.php
include 'db.php';
session_start();

if (!isset($_SESSION['patient_id'])) {
    die("Please login first. <a href='patient_login.php'>Login</a>");
}

$patient_id = intval($_SESSION['patient_id']);
$message = "";
$error = "";

// Handle payment submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bill_id = intval($_POST['bill_id']);
    $payment_method = $_POST['payment_method'];
    
    // Validate payment method
    if (!in_array($payment_method, ['Cash', 'Credit Card'])) {
        $error = "Invalid payment method!";
    } else {
        // Check if bill exists and belongs to this patient
        $check_sql = "SELECT * FROM bill WHERE bill_id = ? AND patient_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $bill_id, $patient_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows == 0) {
            $error = "Bill not found or doesn't belong to you!";
        } else {
            $bill_data = $check_result->fetch_assoc();
            
            // Check if already paid
            if ($bill_data['payment_date']) {
                $error = "This bill has already been paid!";
            } else {
                // Process payment based on method
                if ($payment_method == 'Cash') {
                    // Cash payment - direct processing
                    $update_sql = "UPDATE bill 
                                   SET payment_method = ?, 
                                       payment_date = NOW() 
                                   WHERE bill_id = ?";
                    
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("si", $payment_method, $bill_id);
                    
                    if ($update_stmt->execute()) {
                        $message = "Payment successful! Bill paid with Cash.";
                    } else {
                        $error = "Payment failed: " . $update_stmt->error;
                    }
                    $update_stmt->close();
                    
                } elseif ($payment_method == 'Credit Card') {
                    // Credit card payment - redirect to card details page
                    header("Location: process_card_payment.php?bill_id=$bill_id");
                    exit();
                }
            }
        }
        $check_stmt->close();
    }
}

// Get unpaid bills for this patient
$sql = "SELECT * FROM bill 
        WHERE patient_id = ? AND payment_date IS NULL 
        ORDER BY due_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Bill</title>
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
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .bill-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .bill-card:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .bill-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .bill-id {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
        }

        .bill-amount {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .bill-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 20px;
        }

        .detail-item {
            padding: 8px 0;
        }

        .detail-label {
            color: #666;
            font-size: 14px;
        }

        .detail-value {
            font-weight: 600;
            color: #333;
        }

        .payment-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .payment-option {
            position: relative;
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-label {
            display: block;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }

        .payment-option input[type="radio"]:checked + .payment-label {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .payment-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .payment-name {
            font-weight: bold;
            font-size: 16px;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .no-bills {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .due-soon {
            background: #fff3cd;
            color: #856404;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>💳 Pay Your Bills</h2>

        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($bill = $result->fetch_assoc()): ?>
                <div class="bill-card">
                    <div class="bill-header">
                        <div class="bill-id">Bill #<?php echo $bill['bill_id']; ?></div>
                        <div class="bill-amount">₹<?php echo number_format($bill['total_amount'], 2); ?></div>
                    </div>

                    <div class="bill-details">
                        <div class="detail-item">
                            <div class="detail-label">Consultation Fee</div>
                            <div class="detail-value">₹<?php echo number_format($bill['consultation_fee'], 2); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Medicine Cost</div>
                            <div class="detail-value">₹<?php echo number_format($bill['medicine_cost'], 2); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Discount</div>
                            <div class="detail-value">₹<?php echo number_format($bill['discount_amount'], 2); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Due Date</div>
                            <div class="detail-value">
                                <?php 
                                $due_date = $bill['due_date'];
                                echo date('d M Y', strtotime($due_date));
                                
                                // Check if due soon
                                $days_left = (strtotime($due_date) - time()) / (60 * 60 * 24);
                                if ($days_left <= 7 && $days_left >= 0) {
                                    echo " <span class='due-soon'>Due in " . ceil($days_left) . " days</span>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="bill_id" value="<?php echo $bill['bill_id']; ?>">
                        
                        <div class="payment-section">
                            <h3 style="margin-bottom: 15px; color: #333;">Select Payment Method</h3>
                            
                            <div class="payment-methods">
                                <div class="payment-option">
                                    <input type="radio" id="cash_<?php echo $bill['bill_id']; ?>" 
                                           name="payment_method" value="Cash" required>
                                    <label for="cash_<?php echo $bill['bill_id']; ?>" class="payment-label">
                                        <div class="payment-icon">💵</div>
                                        <div class="payment-name">Cash</div>
                                        <div style="font-size: 12px; margin-top: 5px;">Pay in cash</div>
                                    </label>
                                </div>

                                <div class="payment-option btn:disabled" >
                                    <input type="radio" id="card_<?php echo $bill['bill_id']; ?>" 
                                           name="payment_method" value="Credit Card" required>
                                    <label for="card_<?php echo $bill['bill_id']; ?>" class="payment-label">
                                        <div class="payment-icon">💳</div>
                                        <div class="payment-name">Credit Card</div>
                                        <div style="font-size: 12px; margin-top: 5px;">Pay by card</div>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn">
                                
                                Pay Tk.<?php echo number_format($bill['total_amount'], 2); ?>
                                
                            </button>
                        </div>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-bills">
                <h3>✓ All Bills Paid!</h3>
                <p>You don't have any pending bills.</p>
            </div>
        <?php endif; ?>

        <a href="pay_the_bill.php" class="back-link">← View All Bills</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>