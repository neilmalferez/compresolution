<?php
require_once '../includes/auth.php';
Auth::checkRole(['admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">Admin Portal</a>
        <div class="navbar-menu">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="complaints.php" class="nav-link">All Complaints</a>
            <a href="users.php" class="nav-link">User Management</a>
            <a href="reports.php" class="nav-link">Reports</a>
            <a href="../logout.php" class="nav-link">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header">System Settings</div>
            <div class="card-body">
                <p style="color: var(--text-light);">System settings panel - Configure notification preferences, complaint categories, and system parameters.</p>
                <div style="margin-top: 2rem;">
                    <button class="btn btn-primary" disabled>Save Settings</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>