<?php
require_once 'db.php';
require_once 'lang.php';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$message = '';
$edit_data = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $nim = trim(htmlspecialchars($_POST['nim']));
        $nama_lengkap = trim(htmlspecialchars($_POST['nama_lengkap']));
        $program_studi = trim(htmlspecialchars($_POST['program_studi']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

        if (empty($nim) || empty($nama_lengkap) || empty($program_studi) || empty($email)) {
            $message = __('err_empty');
        } else {
            if ($_POST['action'] === 'add') {
                $stmt = $pdo->prepare("INSERT INTO mahasiswa (nim, nama_lengkap, program_studi, email) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$nim, $nama_lengkap, $program_studi, $email])) {
                    $message = __('success_add');
                }
            } elseif ($_POST['action'] === 'update') {
                $id = intval($_POST['id']);
                $stmt = $pdo->prepare("UPDATE mahasiswa SET nim = ?, nama_lengkap = ?, program_studi = ?, email = ? WHERE id = ?");
                if ($stmt->execute([$nim, $nama_lengkap, $program_studi, $email, $id])) {
                    $message = __('success_update');
                }
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = __('success_delete');
    }
}

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
    $stmt->execute([$id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

$stmt = $pdo->query("SELECT * FROM mahasiswa ORDER BY id DESC");
$mahasiswa_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo __('app_title'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .nav-links a, .lang-switcher a { text-decoration: none; padding: 6px 12px; background: #6c757d; color: white; border-radius: 4px; margin-left: 5px; font-size: 14px; }
        .nav-links a.logout { background: #dc3545; }
        .lang-switcher a { background: #17a2b8; }
        .alert { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .form-container { background: #f1f3f5; padding: 15px; border-radius: 6px; margin-bottom: 25px; }
        .form-group { margin-bottom: 12px; }
        label { display: block; margin-bottom: 4px; font-weight: bold; font-size: 14px; }
        input[type="text"], input[type="email"] { width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box; }
        button { padding: 8px 15px; background: #28a745; border: none; color: white; border-radius: 4px; cursor: pointer; font-size: 15px; }
        button:hover { background: #218838; }
        .btn-secondary { background: #6c757d; text-decoration: none; display: inline-block; padding: 8px 15px; color: white; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #dee2e6; padding: 10px; text-align: left; font-size: 14px; }
        th { background: #343a40; color: white; }
        .action-btns a { text-decoration: none; padding: 4px 8px; border-radius: 3px; color: white; font-size: 12px; margin-right: 4px; }
        .btn-edit { background: #ffc107; color: #212529 !important; }
        .btn-delete { background: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?php echo __('dashboard'); ?></h2>
            <div>
                <span class="lang-switcher">
                    <a href="?change_lang=id">ID</a>
                    <a href="?change_lang=en">EN</a>
                </span>
                <span class="nav-links">
                    <a href="profile.php" class="btn-profile" style="background: #17a2b8;"><?php echo __('profile'); ?></a>
                    <a href="login.php" class="logout"><?php echo __('logout'); ?></a>
                </span>
            </div>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <div class="form-container">
            <h3><?php echo $edit_data ? __('edit') . ' Mahasiswa' : __('add_data'); ?></h3>
            <form method="POST" action="">
                <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'add'; ?>">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label><?php echo __('nim'); ?></label>
                    <input type="text" name="nim" value="<?php echo $edit_data ? htmlspecialchars($edit_data['nim']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label><?php echo __('nama'); ?></label>
                    <input type="text" name="nama_lengkap" value="<?php echo $edit_data ? htmlspecialchars($edit_data['nama_lengkap']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label><?php echo __('prodi'); ?></label>
                    <input type="text" name="program_studi" value="<?php echo $edit_data ? htmlspecialchars($edit_data['program_studi']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label><?php echo __('email'); ?></label>
                    <input type="email" name="email" value="<?php echo $edit_data ? htmlspecialchars($edit_data['email']) : ''; ?>" required>
                </div>
                
                <button type="submit"><?php echo $edit_data ? __('update') : __('save'); ?></button>
                <?php if ($edit_data): ?>
                    <a href="index.php" class="btn-secondary"><?php echo __('cancel'); ?></a>
                <?php endif; ?>
            </form>
        </div>

        <h3><?php echo __('dashboard'); ?></h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th><?php echo __('nim'); ?></th>
                    <th><?php echo __('nama'); ?></th>
                    <th><?php echo __('prodi'); ?></th>
                    <th><?php echo __('email'); ?></th>
                    <th><?php echo __('action'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($mahasiswa_list) > 0): ?>
                    <?php $no = 1; foreach ($mahasiswa_list as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nim']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?php echo htmlspecialchars($row['program_studi']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="action-btns">
                                <a href="index.php?edit=<?php echo $row['id']; ?>" class="btn-edit"><?php echo __('edit'); ?></a>
                                <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('<?php echo __('confirm_delete'); ?>');"><?php echo __('delete'); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #777;"><?php echo __('no_data'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>