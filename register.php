<?php
require_once 'db.php';
require_once 'lang.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = __('err_empty');
    } elseif ($password !== $confirm_password) {
        $error = ($_SESSION['lang'] === 'id') ? 'Konfirmasi password tidak cocok!' : 'Passwords do not match!';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $error = __('err_username_exist');
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($insertStmt->execute([$username, $hashedPassword])) {
                $success = __('success_register');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo __('register_title'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .register-card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 320px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; font-size: 14px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; border: none; color: white; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #218838; }
        .error { color: #dc3545; font-size: 13px; margin-bottom: 15px; text-align: center; }
        .success { color: #28a745; font-size: 13px; margin-bottom: 15px; text-align: center; }
        .links { text-align: center; margin-top: 15px; font-size: 14px; }
        .links a { text-decoration: none; color: #007bff; }
        .links a:hover { text-decoration: underline; }
        .lang-switch { text-align: center; margin-top: 15px; }
        .lang-switch a { text-decoration: none; color: #007bff; margin: 0 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="register-card">
        <h2><?php echo __('register_title'); ?></h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label><?php echo __('username'); ?></label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label><?php echo __('password'); ?></label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label><?php echo $_SESSION['lang'] === 'id' ? 'Konfirmasi Password' : 'Confirm Password'; ?></label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit"><?php echo __('register_btn'); ?></button>
        </form>

        <div class="links">
            <a href="login.php"><?php echo __('have_account'); ?></a>
        </div>

        <div class="lang-switch">
            <a href="?change_lang=id">ID</a> | <a href="?change_lang=en">EN</a>
        </div>
    </div>
</body>
</html>