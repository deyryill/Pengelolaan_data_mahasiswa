<?php
require_once 'db.php';
require_once 'lang.php';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$message = '';
$error = '';
$current_username = $_SESSION['username'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$current_username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim(htmlspecialchars($_POST['username']));
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    if (empty($current_password) || empty($new_username)) {
        $error = __('err_empty');
    } elseif (!password_verify($current_password, $user['password'])) {
        $error = __('err_current_password');
    } else {
        if ($new_username !== $current_username) {
            $checkStmt = $pdo->prepare("SELECT username FROM users WHERE username = ?");
            $checkStmt->execute([$new_username]);
            if ($checkStmt->rowCount() > 0) {
                $error = __('err_username_exist');
            }
        }

        if (empty($error)) {
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $updateStmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE username = ?");
                $success_exec = $updateStmt->execute([$new_username, $hashed_password, $current_username]);
            } else {
                $updateStmt = $pdo->prepare("UPDATE users SET username = ? WHERE username = ?");
                $success_exec = $updateStmt->execute([$new_username, $current_username]);
            }

            if ($success_exec) {
                $_SESSION['username'] = $new_username;
                $current_username = $new_username;
                $message = __('success_profile');
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$current_username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo __('profile_title'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .profile-card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #333; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; font-size: 14px; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; border: none; color: white; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: #dc3545; font-size: 13px; margin-bottom: 15px; text-align: center; }
        .success { color: #28a745; font-size: 13px; margin-bottom: 15px; text-align: center; }
        .back-link { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #6c757d; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="profile-card">
        <h2><?php echo __('profile_title'); ?></h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
            <div class="success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label><?php echo __('new_username'); ?></label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label><?php echo __('new_password'); ?></label>
                <input type="password" name="new_password" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            <div class="form-group">
                <label><?php echo __('current_password'); ?></label>
                <input type="password" name="current_password" required placeholder="Masukkan password lama untuk konfirmasi">
            </div>
            <button type="submit"><?php echo __('update'); ?></button>
        </form>
        <a href="index.php" class="back-link">&larr; <?php echo $_SESSION['lang'] === 'id' ? 'Kembali ke Dashboard' : 'Back to Dashboard'; ?></a>
    </div>
</body>
</html>