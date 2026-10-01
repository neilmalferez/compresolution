<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

Auth::checkRole(['admin']);
$system = new ComplaintSystem();
$stats = $system->getStats();

// Get recent data
$recentComplaints = $system->getComplaints();
$recentComplaints = array_slice($recentComplaints, 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Complaint System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">Admin Portal</a>
        <div class="navbar-menu">
            <a href="dashboard.php" class="nav-link active">Dashboard</a>
            <a href="complaints.php" class="nav-link">All Complaints</a>
            <a href="users.php" class="nav-link">User Management</a>
            <a href="reports.php" class="nav-link">Reports</a>
            <a href="../logout.php" class="nav-link">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-header">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo substr($_SESSION['user_name'], 0, 1); ?>
                </div>
                <div>
                    <h2 style="margin: 0;">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
                    <p style="margin: 0; color: var(--text-light);">System Administrator</p>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Total Complaints</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending']; ?></div>
                <div class="stat-label">Pending Action</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['resolved_today'] ?? 0; ?></div>
                <div class="stat-label">Resolved Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total'] - $stats['pending']; ?></div>
                <div class="stat-label">Total Resolved</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Recent Complaints</div>
            <div class="card-body">
                <table style="width: 100%;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border);">
                            <th style="text-align: left; padding: 0.75rem;">ID</th>
                            <th style="text-align: left; padding: 0.75rem;">Title</th>
                            <th style="text-align: left; padding: 0.75rem;">Student</th>
                            <th style="text-align: left; padding: 0.75rem;">Priority</th>
                            <th style="text-align: left; padding: 0.75rem;">Assigned To</th>
                            <th style="text-align: left; padding: 0.75rem;">Status</th>
                            <th style="text-align: left; padding: 0.75rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentComplaints as $complaint): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 0.75rem;">#<?php echo $complaint['id']; ?></td>
                            <td style="padding: 0.75rem;"><?php echo htmlspecialchars($complaint['title']); ?></td>
                            <td style="padding: 0.75rem;"><?php echo htmlspecialchars($complaint['student_name']); ?></td>
                            <td style="padding: 0.75rem;">
                                <span style="color: var(--<?php echo $complaint['priority'] == 'urgent' ? 'danger' : ($complaint['priority'] == 'high' ? 'warning' : 'text-light'); ?>);">
                                    <?php echo ucfirst($complaint['priority']); ?>
                                </span>
                            </td>
                            <td style="padding: 0.75rem;">
                                <?php echo $complaint['teacher_name'] ? htmlspecialchars($complaint['teacher_name']) : '<span style="color: var(--warning);">Unassigned</span>'; ?>
                            </td>
                            <td style="padding: 0.75rem;">
                                <span class="badge badge-<?php echo $complaint['status']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                                </span>
                            </td>
                            <td style="padding: 0.75rem;">
                                <a href="complaints.php?view=<?php echo $complaint['id']; ?>" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                    View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>