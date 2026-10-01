<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

Auth::checkRole(['teacher']);
$system = new ComplaintSystem();
$stats = $system->getStats();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Complaint System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">Teacher Portal</a>
        <div class="navbar-menu">
            <a href="dashboard.php" class="nav-link active">Dashboard</a>
            <a href="complaints.php" class="nav-link">Assigned Complaints</a>
            <a href="profile.php" class="nav-link">Profile</a>
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
                    <p style="margin: 0; color: var(--text-light);">Teacher Dashboard</p>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Assigned Complaints</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending']; ?></div>
                <div class="stat-label">Pending Action</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total'] - $stats['pending']; ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Recent Assigned Complaints</div>
            <div class="card-body">
                <?php
                $recentComplaints = $system->getComplaints();
                $recentComplaints = array_slice($recentComplaints, 0, 5);

                if (empty($recentComplaints)):
                ?>
                    <p style="text-align: center; color: var(--text-light);">
                        No complaints assigned yet.
                    </p>
                <?php else: ?>
                    <table style="width: 100%;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border);">
                                <th style="text-align: left; padding: 0.75rem;">ID</th>
                                <th style="text-align: left; padding: 0.75rem;">Title</th>
                                <th style="text-align: left; padding: 0.75rem;">Student</th>
                                <th style="text-align: left; padding: 0.75rem;">Priority</th>
                                <th style="text-align: left; padding: 0.75rem;">Status</th>
                                <th style="text-align: left; padding: 0.75rem;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentComplaints as $complaint): ?>
                                <tr>
                                    <td style="padding: 0.75rem;">#<?php echo $complaint['id']; ?></td>
                                    <td style="padding: 0.75rem;"><?php echo htmlspecialchars($complaint['title']); ?></td>
                                    <td style="padding: 0.75rem;"><?php echo htmlspecialchars($complaint['student_name']); ?></td>
                                    <td style="padding: 0.75rem;">
                                        <span style="color: var(--<?php echo $complaint['priority'] == 'urgent' ? 'danger' : ($complaint['priority'] == 'high' ? 'warning' : 'text-light'); ?>);">
                                            <?php echo ucfirst($complaint['priority']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <span class="badge badge-<?php echo $complaint['status']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <a href="complaints.php?view=<?php echo $complaint['id']; ?>" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>