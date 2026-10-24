<?php
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

$raw_name = isset($_POST['name']) ? $_POST['name'] : '';
$raw_address = isset($_POST['address']) ? $_POST['address'] : '';
$raw_mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$raw_month = isset($_POST['month']) ? $_POST['month'] : '';
$raw_units = isset($_POST['units']) ? $_POST['units'] : '';

$errors = [];

$name = trim($raw_name);
if (empty($name)) {
    $errors[] = "Customer Name is required.";
} elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
    $errors[] = "Customer Name can only contain alphabets and spaces.";
}

$address = trim($raw_address);
if (empty($address)) {
    $errors[] = "Customer Address is required.";
}

$mobile = trim($raw_mobile);
if (empty($mobile)) {
    $errors[] = "Mobile Number is required.";
} elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
    $errors[] = "Mobile Number must be exactly 10 digits and contain only numbers.";
}

$valid_months = [
    "January", "February", "March", "April", "May", "June", 
    "July", "August", "September", "October", "November", "December"
];
$month = trim($raw_month);
if (empty($month)) {
    $errors[] = "Billing Month is required.";
} elseif (!in_array($month, $valid_months)) {
    $errors[] = "Invalid Billing Month selected.";
}

if ($raw_units === '') {
    $errors[] = "Units consumed is required.";
} else {
    $units = intval($raw_units);
    if ($units <= 0) {
        $errors[] = "Units consumed must be a positive number greater than zero.";
    }
}

if (!empty($errors)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Validation Errors - Light Bill Calculator</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="bg-light flex-center">
        <div class="card error-card">
            <div class="error-badge">❌</div>
            <h2 class="text-danger">Validation Failed</h2>
            <div class="error-steps" style="margin-top: 15px;">
                <h3>Please correct the following errors:</h3>
                <ul style="padding-left: 20px; color: var(--text-color);">
                    <?php foreach ($errors as $error): ?>
                        <li style="margin-bottom: 8px;"><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="text-center" style="margin-top: 24px;">
                <a href="index.php" class="btn btn-secondary">Go Back & Correct</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

if ($units <= 50) {
    $rate = 3.50;
} elseif ($units <= 100) {
    $rate = 4.00;
} elseif ($units <= 200) {
    $rate = 5.20;
} else {
    $rate = 6.50;
}

$total_bill = $units * $rate;

$bill_date = date('d-m-Y');
$bill_time = date('h:i A');

try {
    $insert_query = "INSERT INTO `electricity_bill` (`customer_name`, `address`, `mobile`, `bill_month`, `units`, `rate`, `total_bill`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "ssssidd", $name, $address, $mobile, $month, $units, $rate, $total_bill);
    mysqli_stmt_execute($stmt);
    $bill_id = mysqli_insert_id($conn);
    $bill_number = sprintf("LBC-%05d", $bill_id);
    mysqli_stmt_close($stmt);

} catch (mysqli_sql_exception $e) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Error - Light Bill Calculator</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="bg-light flex-center">
        <div class="card error-card">
            <div class="error-badge">⚠️</div>
            <h2 class="text-danger">Failed to Save Record</h2>
            <p class="error-desc"><?php echo htmlspecialchars($e->getMessage()); ?></p>
            <p>Your bill calculation is complete, but the record could not be saved to the database.</p>
            <div class="text-center" style="margin-top: 24px;">
                <a href="index.php" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Bill Receipt - <?php echo $bill_number; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

    <div class="container">
        <div class="card">
            <div class="alert-success text-center">
                ✓ Data Saved Successfully.
            </div>

            <div class="invoice-box">
                <div class="invoice-header text-center">
                    <h1 class="invoice-title">LIGHT BILL</h1>
                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 8px;">
                        <div><strong>Bill No:</strong> <?php echo htmlspecialchars($bill_number); ?></div>
                        <div><strong>Date:</strong> <?php echo $bill_date; ?> | <strong>Time:</strong> <?php echo $bill_time; ?></div>
                    </div>
                </div>

                <div class="invoice-body">
                    <div class="invoice-row">
                        <span class="invoice-label">Customer Name</span>
                        <span class="invoice-value"><?php echo htmlspecialchars($name); ?></span>
                    </div>

                    <div class="invoice-row">
                        <span class="invoice-label">Address</span>
                        <span class="invoice-value" style="text-align: right; max-width: 60%; word-break: break-word;"><?php echo nl2br(htmlspecialchars($address)); ?></span>
                    </div>

                    <div class="invoice-row">
                        <span class="invoice-label">Mobile</span>
                        <span class="invoice-value"><?php echo htmlspecialchars($mobile); ?></span>
                    </div>

                    <div class="invoice-row">
                        <span class="invoice-label">Month</span>
                        <span class="invoice-value"><?php echo htmlspecialchars($month); ?></span>
                    </div>

                    <div class="invoice-row">
                        <span class="invoice-label">Units Consumed</span>
                        <span class="invoice-value"><?php echo number_format($units); ?> units</span>
                    </div>

                    <div class="invoice-row">
                        <span class="invoice-label">Rate per Unit</span>
                        <span class="invoice-value">₹<?php echo number_format($rate, 2); ?></span>
                    </div>

                    <div class="invoice-row invoice-total">
                        <span class="invoice-label" style="font-size: 1.15rem; color: #0f172a;">Total Bill</span>
                        <span class="invoice-value">₹<?php echo number_format($total_bill, 2); ?></span>
                    </div>
                </div>
                <div class="barcode-mock">
                    <div class="barcode-line thin"></div>
                    <div class="barcode-line wide"></div>
                    <div class="barcode-line"></div>
                    <div class="barcode-line thin"></div>
                    <div class="barcode-line wide"></div>
                    <div class="barcode-line"></div>
                    <div class="barcode-line thin"></div>
                    <div class="barcode-line"></div>
                    <div class="barcode-line wide"></div>
                    <div class="barcode-line thin"></div>
                    <div class="barcode-line"></div>
                </div>
            </div>

            <div class="btn-group">
                <a href="index.php" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Calculate New Bill
                </a>
                <button onclick="window.print();" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.617 0-1.11-.483-1.12-1.1L6 18m12 0h-12m12 0v-1.591a3 3 0 0 0-.82-2.15l-1.061-1.06a3 3 0 0 0-2.12-.88H10c-.795 0-1.558.315-2.12.879l-1.06 1.06a3 3 0 0 0-.82 2.151V18" />
                    </svg>
                    Print Bill
                </button>
            </div>
        </div>
    </div>

</body>
</html>
