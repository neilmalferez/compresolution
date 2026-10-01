<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

Auth::checkRole(['admin']);
$system = new ComplaintSystem();
$message = '';

// Handle user creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $data = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'email' => $_POST['email'],
        'role' => $_POST['role'],
        'full_name' => $_POST['full_name'],
        'student_id' => $_POST['student_id'] ?? null,
        'department' => $_POST['department'] ?? null
    ];

    if ($system->createUser($data)) {
        $message = '<div class="alert alert-success">User created successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Failed to create user. Username or email may already exist.</div>';
    }
}

$users = $system->getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">Admin Portal</a>
        <div class="navbar-menu">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="complaints.php" class="nav-link">All Complaints</a>
            <a href="users.php" class="nav-link active">User Management</a>
            <a href="reports.php" class="nav-link">Reports</a>
            <a href="../logout.php" class="nav-link">Logout</a>
        </div>
    </nav>

    <div class="container">
        <?php echo $message; ?>

        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-header">Create New User</div>
            <div class="card-body">
                <form method="POST">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-control" required onchange="toggleStudentFields(this.value)">
                                <option value="">Select Role</option>
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group" id="student_id_field" style="display: none;">
                            <label class="form-label">Student ID</label>
                            <input type="text" name="student_id" class="form-control">
                        </div>
                        <div class="form-group" id="department_field" style="display: none;">
                            <label class="form-label">Department</label>
                            <input type="text" name="department" class="form-control">
                        </div>
                    </div>
                    <div style="margin-top: 1rem;">
                        <button type="submit" name="create_user" class="btn btn-primary">Create User</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">All Users (<?php echo count($users); ?>)</div>
            <div class="card-body" style="padding: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Student ID</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>#<?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><span class="role-<?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span></td>
                            <td><?php echo $user['student_id'] ? htmlspecialchars($user['student_id']) : '-'; ?></td>
                            <td><?php echo $user['department'] ? htmlspecialchars($user['department']) : '-'; ?></td>
                            <td><?php echo ucfirst($user['status']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function toggleStudentFields(role) {
        const studentField = document.getElementById('student_id_field');
        const deptField = document.getElementById('department_field');
        
        if (role === 'student') {
            studentField.style.display = 'block';
            deptField.style.display = 'block';
        } else {
            studentField.style.display = 'none';
            deptField.style.display = 'none';
        }
    }
    </script>
</body>
</html>