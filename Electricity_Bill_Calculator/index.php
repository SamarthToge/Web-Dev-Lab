<?php
date_default_timezone_set('Asia/Kolkata');

require_once 'db.php';

$months = [
    "January", "February", "March", "April", "May", "June", 
    "July", "August", "September", "October", "November", "December"
];

$current_month = date('F');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Light Bill Calculator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="wrapper">
        <div class="grid-layout">
            
            <div class="card">
                <h2 class="card-title text-center">Light Bill Calculator</h2>
                <p class="card-subtitle text-center">Calculate and record monthly electricity billing records</p>
                
                <div class="meta-display">
                    <span><strong>Date:</strong> <?php echo date('d-m-Y'); ?></span>
                    <span><strong>Time:</strong> <?php echo date('h:i A'); ?></span>
                </div>

                <form action="calculate.php" method="POST" autocomplete="off">
                    
                    <div class="form-group">
                        <label for="name">Customer Name</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Enter full name (e.g. Rahul Patil)" 
                                pattern="[A-Za-z\s]+" 
                                title="Only alphabets and spaces are allowed." 
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Customer Address</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="align-self: flex-start; margin-top: 14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <textarea 
                                id="address" 
                                name="address" 
                                placeholder="Enter billing address" 
                                required
                            ></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.554-5.147-3.877-6.699-6.699l1.293-.97c.362-.272.528-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            <input 
                                type="text" 
                                id="mobile" 
                                name="mobile" 
                                placeholder="Enter 10-digit mobile number" 
                                pattern="\d{10}" 
                                title="Must be exactly 10 digits." 
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="month">Billing Month</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            <select id="month" name="month" required>
                                <option value="" disabled>-- Select Month --</option>
                                <?php foreach ($months as $m): ?>
                                    <option value="<?php echo $m; ?>" <?php echo ($m === $current_month) ? 'selected' : ''; ?>>
                                        <?php echo $m; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="units">Units Consumed</label>
                        <div class="input-icon-wrapper">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                            </svg>
                            <input 
                                type="number" 
                                id="units" 
                                name="units" 
                                placeholder="e.g. 120" 
                                min="1" 
                                title="Units must be a positive number." 
                                required
                            >
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="reset" class="btn btn-secondary">Reset Fields</button>
                        <button type="submit" class="btn btn-primary">Generate Bill</button>
                    </div>

                </form>
            </div>

            <div class="card info-card">
                <h3 class="info-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px; color: var(--primary-color);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                    Slab Rates Directory
                </h3>
                <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px;">
                    Electricity charges are computed flatly based on the consumption tier. Find the slab details below:
                </p>

                <ul class="slab-list">
                    <li class="slab-item">
                        <span class="slab-range">0 to 50 Units</span>
                        <span class="slab-badge badge-green">₹3.50 / Unit</span>
                    </li>
                    <li class="slab-item">
                        <span class="slab-range">51 to 100 Units</span>
                        <span class="slab-badge badge-teal">₹4.00 / Unit</span>
                    </li>
                    <li class="slab-item">
                        <span class="slab-range">101 to 200 Units</span>
                        <span class="slab-badge badge-indigo">₹5.20 / Unit</span>
                    </li>
                    <li class="slab-item">
                        <span class="slab-range">Above 200 Units</span>
                        <span class="slab-badge badge-rose">₹6.50 / Unit</span>
                    </li>
                </ul>

                <div class="guidelines-box">
                    <h4>Billing Rules</h4>
                    <div class="guideline-item">
                        Enter names containing only alphabets and spaces.
                    </div>
                    <div class="guideline-item">
                        Input a valid 10-digit mobile number for notification logs.
                    </div>
                    <div class="guideline-item">
                        Bill is computed as <code>Units × Slab Rate</code>.
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
