<?php
require 'user_info.php';

// Get user data
$ip      = get_ip();
$device  = get_device();
$os      = get_os();
$browser = get_user_browser();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>User Info with PHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font (Persian-friendly) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;600;800&display=swap" rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<main class="container">

    <header class="header">
        <h1> User Info With PHP</h1>
        <p>
            This data is collected from browser headers using PHP $_SERVER variables.
        </p>
    </header>

    <section class="grid">

        <div class="card">
            <span class="label"> IP Address</span>
            <span class="value" dir="ltr"><?php echo htmlspecialchars($ip); ?></span>
        </div>

        <div class="card">
            <span class="label"> Device</span>
            <span class="value"><?php echo htmlspecialchars($device); ?></span>
        </div>

        <div class="card">
            <span class="label"> Operating System</span>
            <span class="value"><?php echo htmlspecialchars($os); ?></span>
        </div>

        <div class="card">
            <span class="label"> Browser</span>
            <span class="value"><?php echo htmlspecialchars($browser); ?></span>
        </div>

    </section>

    <footer class="footer">
        Built with PHP + HTML + CSS
    </footer>

</main>

</body>
</html>
