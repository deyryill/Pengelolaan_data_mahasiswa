<?php
require_once 'db.php';
require_once 'lang.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = __('err_empty');
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = __('err_login');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo __('login_title'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 320px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; border: none; color: white; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: #dc3545; font-size: 14px; margin-bottom: 15px; text-align: center; }
        .lang-switch { text-align: center; margin-top: 15px; }
        .lang-switch a { text-decoration: none; color: #007bff; margin: 0 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2><?php echo __('login_title'); ?></h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
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
            <button type="submit"><?php echo __('login_btn'); ?></button>
        </form>
        <div class="links" style="text-align: center; margin-top: 15px; font-size: 14px;">
            <a href="register.php" style="text-decoration: none; color: #007bff;"><?php echo __('no_account'); ?></a>
        </div>
        <div class="lang-switch">
            <a href="?change_lang=id">ID</a> | <a href="?change_lang=en">EN</a>
        </div>
    </div>
</body>
</html>