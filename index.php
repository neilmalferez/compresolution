<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Complaint Resolution System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">School Complaint System</a>
        <div class="navbar-menu">
            <a href="login.php" class="nav-link">Login</a>
            <a href="#about" class="nav-link">About</a>
            <a href="#contact" class="nav-link">Contact</a>
        </div>
    </nav>

    <div class="container">
        <div class="hero">
            <h1 style="text-align: center; color: var(--primary); margin-bottom: 1rem;">
                Welcome to School Complaint Resolution System
            </h1>
            <p style="text-align: center; color: var(--text-light); font-size: 1.125rem; max-width: 600px; margin: 0 auto 2rem;">
                A transparent platform for students to raise concerns and for teachers & administrators to resolve them efficiently.
            </p>

            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="login.php" class="btn btn-primary">Get Started</a>
                <a href="#features" class="btn btn-outline">Learn More</a>
            </div>
        </div>

        <div class="features" style="margin-top: 4rem;">
            <div class="dashboard-grid">
                <div class="card">
                    <div class="card-header">For Students</div>
                    <div class="card-body">
                        <ul class="feature-list">
                            <li>Submit complaints easily</li>
                            <li>Track complaint status</li>
                            <li>Receive updates</li>
                            <li>Confidential & secure</li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">For Teachers</div>
                    <div class="card-body">
                        <ul class="feature-list">
                            <li>Review assigned complaints</li>
                            <li>Update progress</li>
                            <li>Communicate with students</li>
                            <li>Resolution tracking</li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">For Admins</div>
                    <div class="card-body">
                        <ul class="feature-list">
                            <li>Oversee all complaints</li>
                            <li>Assign to teachers</li>
                            <li>Generate reports</li>
                            <li>User management</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>