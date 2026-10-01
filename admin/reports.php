<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

Auth::checkRole(['admin']);
$system = new ComplaintSystem();
$db = Database::getInstance()->getConnection();

// Generate basic reports
$report = [];

// Complaints by status
$statusResult = $db->query("
    SELECT status, COUNT(*) as count 
    FROM complaints 
    GROUP BY status
");
$report['by_status'] = $statusResult->fetch_all(MYSQLI_ASSOC);

// Complaints by category
$categoryResult = $db->query("
    SELECT category, COUNT(*) as count 
    FROM complaints 
    GROUP BY category
");
$report['by_category'] = $categoryResult->fetch_all(MYSQLI_ASSOC);

// Complaints by priority
$priorityResult = $db->query("
    SELECT priority, COUNT(*) as count 
    FROM complaints 
    GROUP BY priority
");
$report['by_priority'] = $priorityResult->fetch_all(MYSQLI_ASSOC);

// Average resolution time
$avgTimeResult = $db->query("
    SELECT AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours 
    FROM complaints 
    WHERE resolved_at IS NOT NULL
");
$report['avg_resolution'] = $avgTimeResult->fetch_assoc()['avg_hours'];

$avgRow = $avgTimeResult->fetch_assoc();
$report['avg_resolution'] = $avgRow['avg_hours'] ?? 0;

// Teacher workload
$workloadResult = $db->query("
    SELECT u.full_name, COUNT(c.id) as complaint_count 
    FROM users u
    LEFT JOIN complaints c ON u.id = c.assigned_teacher_id
    WHERE u.role = 'teacher'
    GROUP BY u.id
");
$report['teacher_workload'] = $workloadResult->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin Portal</title>
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
            <a href="reports.php" class="nav-link active">Reports</a>
            <a href="../logout.php" class="nav-link">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-header">
            <h2>System Reports & Analytics</h2>
            <button onclick="window.print()" class="btn btn-primary">Print Report</button>
        </div>

        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($report['avg_resolution'], 1); ?>h</div>
                <div class="stat-label">Average Resolution Time</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Complaints by Status</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = array_sum(array_column($report['by_status'], 'count'));
                        foreach ($report['by_status'] as $item): 
                            $percentage = ($total > 0) ? ($item['count'] / $total * 100) : 0;
                        ?>
                        <tr>
                            <td><?php echo ucfirst(str_replace('_', ' ', $item['status'])); ?></td>
                            <td><?php echo $item['count']; ?></td>
                            <td><?php echo number_format($percentage, 1); ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="margin-top: 2rem;">
            <div class="card-header">Complaints by Category</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['by_category'] as $item): ?>
                        <tr>
                            <td><?php echo ucfirst($item['category']); ?></td>
                            <td><?php echo $item['count']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="margin-top: 2rem;">
            <div class="card-header">Teacher Workload</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Teacher Name</th>
                            <th>Assigned Complaints</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['teacher_workload'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['full_name']); ?></td>
                            <td><?php echo $item['complaint_count']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>