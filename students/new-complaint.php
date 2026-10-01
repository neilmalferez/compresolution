<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

Auth::checkRole(['student']);
$system = new ComplaintSystem();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'category' => $_POST['category'],
        'priority' => $_POST['priority']
    ];

    if ($system->createComplaint($data)) {
        $message = '<div class="alert alert-success">Complaint submitted successfully! <a href="complaints.php">View your complaints</a></div>';
    } else {
        $message = '<div class="alert alert-danger">Failed to submit complaint. Please try again.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Complaint - Student Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">Student Portal</a>
        <div class="navbar-menu">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="complaints.php" class="nav-link">My Complaints</a>
            <a href="new-complaint.php" class="nav-link active">New Complaint</a>
            <a href="profile.php" class="nav-link">Profile</a>
            <a href="../logout.php" class="nav-link">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header">Submit New Complaint</div>
            <div class="card-body">
                <?php echo $message; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Complaint Title</label>
                        <input type="text" name="title" class="form-control" required
                            placeholder="Brief title of your complaint">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="academic">Academic Issue</option>
                            <option value="facility">Facility/Resource</option>
                            <option value="behavior">Student/Staff Behavior</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Priority Level</label>
                        <select name="priority" class="form-control" required>
                            <option value="low">Low - Minor issue</option>
                            <option value="medium" selected>Medium - Needs attention</option>
                            <option value="high">High - Important issue</option>
                            <option value="urgent">Urgent - Requires immediate action</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Detailed Description</label>
                        <textarea name="description" class="form-control" rows="6" required
                            placeholder="Please provide detailed information about your complaint..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Submit Complaint
                    </button>
                    <a href="dashboard.php" class="btn btn-outline">Cancel</a>
                </form>
            </div>
        </div>

        <div class="card" style="margin-top: 2rem;">
            <div class="card-header">Complaint Guidelines</div>
            <div class="card-body">
                <ul style="margin-left: 1.5rem; color: var(--text-light);">
                    <li>Provide clear and factual information</li>
                    <li>Include specific dates, times, and locations if applicable</li>
                    <li>Be respectful and professional in your description</li>
                    <li>You will receive updates as your complaint is processed</li>
                    <li>False complaints may result in disciplinary action</li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>