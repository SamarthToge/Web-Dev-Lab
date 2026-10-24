<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "lightbill";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    try {
        $conn = mysqli_connect($host, $user, $password, $database);
    } catch (mysqli_sql_exception $e) {
        $conn = mysqli_connect($host, $user, $password, $database, 3307);
    }
    
    mysqli_query($conn, "DESCRIBE `electricity_bill`");

} catch (mysqli_sql_exception $e) {
    $error_msg = $e->getMessage();
    
    $is_db_missing = (strpos($error_msg, "Unknown database") !== false);
    $is_table_missing = (strpos($error_msg, "doesn't exist") !== false);
    
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
            <h2 class="text-danger">Database Configuration Required</h2>
            <p class="error-desc"><?php echo htmlspecialchars($error_msg); ?></p>
            
            <hr>
            
            <div class="error-steps">
                <h3>Follow these steps to fix:</h3>
                <ol>
                    <li>Make sure <strong>XAMPP Control Panel</strong> is open and <strong>MySQL Service</strong> is running (Green status).</li>
                    <?php if ($is_db_missing): ?>
                        <li>Open <a href="http://localhost/phpmyadmin/" target="_blank" class="link">phpMyAdmin</a> in your browser.</li>
                        <li>Create a new database named <code>lightbill</code>.</li>
                        <li>Import the <code>database.sql</code> file from the project directory.</li>
                    <?php elseif ($is_table_missing): ?>
                        <li>Open <a href="http://localhost/phpmyadmin/" target="_blank" class="link">phpMyAdmin</a> in your browser.</li>
                        <li>Select the database <code>lightbill</code>.</li>
                        <li>Click on the <strong>SQL</strong> tab at the top.</li>
                        <li>Open the file <code>database.sql</code> in a text editor, copy its contents, paste them here, and click <strong>Go</strong>.</li>
                    <?php else: ?>
                        <li>Ensure connection credentials in <code>db.php</code> are correct.</li>
                    <?php endif; ?>
                </ol>
            </div>
            
            <div class="text-center" style="margin-top: 24px;">
                <button onclick="window.location.reload();" class="btn btn-primary">Retry Connection</button>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>
