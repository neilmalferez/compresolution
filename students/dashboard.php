<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

Auth::checkRole(['student']);
$system = new ComplaintSystem();
$stats = $system->getStats();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Complaint System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">Student Portal</a>
        <div class="navbar-menu">
            <a href="dashboard.php" class="nav-link active">Dashboard</a>
            <a href="complaints.php" class="nav-link">My Complaints</a>
            <a href="new-complaint.php" class="nav-link">New Complaint</a>
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
                    <p style="margin: 0; color: var(--text-light);">Student ID: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                </div>
            </div>
            <a href="new-complaint.php" class="btn btn-primary">+ New Complaint</a>
        </div>

        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Total Complaints</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending']; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total'] - $stats['pending']; ?></div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Recent Complaints</div>
            <div class="card-body">
                <?php
                $recentComplaints = $system->getComplaints();
                $recentComplaints = array_slice($recentComplaints, 0, 5);

                if (empty($recentComplaints)):
                ?>
                    <p style="text-align: center; color: var(--text-light);">
                        No complaints yet. <a href="new-complaint.php">Submit your first complaint</a>
                    </p>
                <?php else: ?>
                    <table style="width: 100%;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border);">
                                <th style="text-align: left; padding: 0.75rem;">Title</th>
                                <th style="text-align: left; padding: 0.75rem;">Category</th>
                                <th style="text-align: left; padding: 0.75rem;">Status</th>
                                <th style="text-align: left; padding: 0.75rem;">Date</th>
                                <th style="text-align: left; padding: 0.75rem;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentComplaints as $complaint): ?>
                                <tr>
                                    <td style="padding: 0.75rem;"><?php echo htmlspecialchars($complaint['title']); ?></td>
                                    <td style="padding: 0.75rem;"><?php echo ucfirst($complaint['category']); ?></td>
                                    <td style="padding: 0.75rem;">
                                        <span class="badge badge-<?php echo $complaint['status']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem;"><?php echo date('M d, Y', strtotime($complaint['created_at'])); ?></td>
                                    <td style="padding: 0.75rem;">
                                        <a href="complaints.php?view=<?php echo $complaint['id']; ?>" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                            View
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