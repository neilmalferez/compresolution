<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

Auth::checkRole(['student']);
$system = new ComplaintSystem();
$message = '';


if (isset($_GET['view'])) {
    $complaint = $system->getComplaintById($_GET['view']);

    if ($complaint && $complaint['student_id'] == $_SESSION['user_id']) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_text'])) {
            if ($system->addUpdate($_GET['view'], $_POST['update_text'])) {
                $message = '<div class="alert alert-success">Update added successfully!</div>';
                $complaint = $system->getComplaintById($_GET['view']);
            }
        }
        $updates = $system->getUpdates($_GET['view']);
    } else {
        header('Location: complaints.php');
        exit();
    }
}

$filters = [];
if (isset($_GET['status'])) $filters['status'] = $_GET['status'];
if (isset($_GET['category'])) $filters['category'] = $_GET['category'];

$complaints = $system->getComplaints($filters);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Complaints - Student Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">Student Portal</a>
        <div class="navbar-menu">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="complaints.php" class="nav-link active">My Complaints</a>
            <a href="new-complaint.php" class="nav-link">New Complaint</a>
            <a href="profile.php" class="nav-link">Profile</a>
            <a href="../logout.php" class="nav-link">Logout</a>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($_GET['view'])): ?>
            <!-- Single Complaint View -->
            <div class="complaint-detail">
                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 1rem;">
                    <h2><?php echo htmlspecialchars($complaint['title']); ?></h2>
                    <span class="badge badge-<?php echo $complaint['status']; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                    </span>
                </div>

                <?php echo $message; ?>

                <div class="complaint-meta">
                    <div>
                        <strong>Category:</strong> <?php echo ucfirst($complaint['category']); ?>
                    </div>
                    <div>
                        <strong>Priority:</strong> <?php echo ucfirst($complaint['priority']); ?>
                    </div>
                    <div>
                        <strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($complaint['created_at'])); ?>
                    </div>
                    <?php if ($complaint['teacher_name']): ?>
                        <div>
                            <strong>Assigned to:</strong> <?php echo htmlspecialchars($complaint['teacher_name']); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="margin: 1.5rem 0;">
                    <h3>Description</h3>
                    <p style="background: var(--light); padding: 1rem; border-radius: 0.375rem;">
                        <?php echo nl2br(htmlspecialchars($complaint['description'])); ?>
                    </p>
                </div>

                <!-- Updates -->
                <div style="margin: 2rem 0;">
                    <h3>Updates & Comments</h3>
                    <?php foreach ($updates as $update): ?>
                        <div class="update-item">
                            <div class="update-header">
                                <span class="role-<?php echo $update['role']; ?>">
                                    <strong><?php echo htmlspecialchars($update['full_name']); ?></strong>
                                    (<?php echo ucfirst($update['role']); ?>)
                                </span>
                                <span><?php echo date('M d, Y H:i', strtotime($update['created_at'])); ?></span>
                            </div>
                            <div><?php echo nl2br(htmlspecialchars($update['update_text'])); ?></div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($updates)): ?>
                        <p style="color: var(--text-light);">No updates yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Add Update -->
                <div class="card" style="margin-top: 2rem;">
                    <div class="card-header">Add Comment/Update</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <textarea name="update_text" class="form-control" rows="3" required
                                    placeholder="Add additional information or comment..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Update</button>
                            <a href="complaints.php" class="btn btn-outline">Back to List</a>
                        </form>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Complaints List -->
            <div class="filters">
                <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; flex: 1;">
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="submitted" <?php echo (isset($_GET['status']) && $_GET['status'] == 'submitted') ? 'selected' : ''; ?>>Submitted</option>
                            <option value="reviewed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'reviewed') ? 'selected' : ''; ?>>Reviewed</option>
                            <option value="in_progress" <?php echo (isset($_GET['status']) && $_GET['status'] == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="resolved" <?php echo (isset($_GET['status']) && $_GET['status'] == 'resolved') ? 'selected' : ''; ?>>Resolved</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <option value="academic" <?php echo (isset($_GET['category']) && $_GET['category'] == 'academic') ? 'selected' : ''; ?>>Academic</option>
                            <option value="facility" <?php echo (isset($_GET['category']) && $_GET['category'] == 'facility') ? 'selected' : ''; ?>>Facility</option>
                            <option value="behavior" <?php echo (isset($_GET['category']) && $_GET['category'] == 'behavior') ? 'selected' : ''; ?>>Behavior</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">My Complaints (<?php echo count($complaints); ?>)</div>
                <div class="card-body" style="padding: 0;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($complaints as $complaint): ?>
                                <tr class="<?php echo ($complaint['priority'] == 'urgent' || $complaint['priority'] == 'high') ? 'priority-high' : ''; ?>">
                                    <td>#<?php echo $complaint['id']; ?></td>
                                    <td><?php echo htmlspecialchars($complaint['title']); ?></td>
                                    <td><?php echo ucfirst($complaint['category']); ?></td>
                                    <td>
                                        <span style="color: var(--<?php echo $complaint['priority'] == 'urgent' ? 'danger' : ($complaint['priority'] == 'high' ? 'warning' : 'text-light'); ?>);">
                                            <?php echo ucfirst($complaint['priority']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $complaint['status']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($complaint['created_at'])); ?></td>
                                    <td>
                                        <a href="?view=<?php echo $complaint['id']; ?>" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($complaints)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; color: var(--text-light);">
                                        No complaints found. <a href="new-complaint.php">Submit your first complaint</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>