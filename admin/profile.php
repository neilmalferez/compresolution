<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

Auth::checkRole(['teacher', 'admin']);
$system = new ComplaintSystem();
$user = $system->getUserById($_SESSION['user_id']);
$role = $_SESSION['user_role'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = '<div class="alert alert-success">Profile updated successfully!</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo ucfirst($role); ?> Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand"><?php echo ucfirst($role); ?> Portal</a>
        <div class="navbar-menu">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="complaints.php" class="nav-link">Complaints</a>
            <?php if ($role === 'admin'): ?>
            <a href="users.php" class="nav-link">Users</a>
            <a href="reports.php" class="nav-link">Reports</a>
            <?php endif; ?>
            <a href="profile.php" class="nav-link active">Profile</a>
            <a href="../logout.php" class="nav-link">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header">My Profile</div>
            <div class="card-body">
                <?php echo $message; ?>
                <form method="POST">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>
                        <?php if ($role === 'teacher'): ?>
                        <div class="form-group">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['department'] ?? ''); ?>">
                        </div>
                        <?php endif; ?>
                    </div>
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>