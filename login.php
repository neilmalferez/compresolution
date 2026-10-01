<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$system = new ComplaintSystem();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $system->getUserByUsername($username);

    if ($user && password_verify($password, $user['password'])) {
        Auth::login($user);


        switch ($user['role']) {
            case 'student':
                header('Location: students/dashboard.php');
                break;
            case 'teacher':
                header('Location: teacher/dashboard.php');
                break;
            case 'admin':
                header('Location: admin/dashboard.php');
                break;
        }
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Complaint System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="login-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10 12 5 2 10l10 5 10-5Z"></path>
                        <path d="M6 12v5c3 2 9 2 12 0v-5"></path>
                        <path d="M22 10v6"></path>
                    </svg>
                </div>
                <h2>Login to Continue</h2>
                <p>Enter your credentials to access the system</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Login
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem; color: var(--text-light);">
                Demo: Use 'admin' / 'admin123'
            </div>
        </div>
    </div>
</body>

</html>