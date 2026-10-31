<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'id';
}

if (isset($_GET['change_lang'])) {
    $lang = $_GET['change_lang'];
    if ($lang === 'id' || $lang === 'en') {
        $_SESSION['lang'] = $lang;
    }
    $redirect_url = strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: " . $redirect_url);
    exit;
}

$translations = [
    'id' => [
        'app_title' => 'Sistem Manajemen Mahasiswa',
        'login_title' => 'Silakan Login',
        'username' => 'Username',
        'password' => 'Password',
        'login_btn' => 'Login',
        'logout' => 'Logout',
        'dashboard' => 'Dashboard Mahasiswa',
        'add_data' => 'Tambah Mahasiswa Baru',
        'nim' => 'NIM',
        'nama' => 'Nama Lengkap',
        'prodi' => 'Program Studi',
        'email' => 'Email',
        'action' => 'Aksi',
        'save' => 'Simpan',
        'update' => 'Perbarui',
        'cancel' => 'Batal',
        'edit' => 'Ubah',
        'delete' => 'Hapus',
        'no_data' => 'Belum ada data mahasiswa.',
        'success_add' => 'Data mahasiswa berhasil ditambahkan!',
        'success_update' => 'Data mahasiswa berhasil diperbarui!',
        'success_delete' => 'Data mahasiswa berhasil dihapus!',
        'err_empty' => 'Semua kolom wajib diisi!',
        'err_login' => 'Username atau password salah!',
        'confirm_delete' => 'Apakah Anda yakin ingin menghapus data ini?',
        'register_title' => 'Daftar Akun Baru',
        'register_btn' => 'Daftar',
        'have_account' => 'Sudah punya akun? Login di sini',
        'no_account' => 'Belum punya akun? Daftar di sini',
        'success_register' => 'Registrasi berhasil! Silakan login dengan akun baru Anda.',
        'err_username_exist' => 'Username sudah digunakan, silakan pilih yang lain!',
        'profile' => 'Profil Saya',
        'profile_title' => 'Pengaturan Profil',
        'current_password' => 'Password Saat Ini (Wajib)',
        'new_username' => 'Username Baru',
        'new_password' => 'Password Baru (Opsional)',
        'success_profile' => 'Profil berhasil diperbarui!',
        'err_current_password' => 'Password saat ini yang Anda masukkan salah!',
    ],
    'en' => [
        'app_title' => 'Student Management System',
        'login_title' => 'Please Login',
        'username' => 'Username',
        'password' => 'Password',
        'login_btn' => 'Login',
        'logout' => 'Logout',
        'dashboard' => 'Student Dashboard',
        'add_data' => 'Add New Student',
        'nim' => 'Student ID (NIM)',
        'nama' => 'Full Name',
        'prodi' => 'Study Program',
        'email' => 'Email',
        'action' => 'Action',
        'save' => 'Save',
        'update' => 'Update',
        'cancel' => 'Cancel',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'no_data' => 'No student data available.',
        'success_add' => 'Student data added successfully!',
        'success_update' => 'Student data updated successfully!',
        'success_delete' => 'Student data deleted successfully!',
        'err_empty' => 'All fields are required!',
        'err_login' => 'Invalid username or password!',
        'confirm_delete' => 'Are you sure you want to delete this data?',
        'register_title' => 'Register New Account',
        'register_btn' => 'Register',
        'have_account' => 'Already have an account? Login here',
        'no_account' => "Don't have an account? Register here",
        'success_register' => 'Registration successful! Please login with your new account.',
        'err_username_exist' => 'Username already taken, please choose another one!',
        'profile' => 'My Profile',
        'profile_title' => 'Profile Settings',
        'current_password' => 'Current Password (Required)',
        'new_username' => 'New Username',
        'new_password' => 'New Password (Optional)',
        'success_profile' => 'Profile updated successfully!',
        'err_current_password' => 'The current password you entered is incorrect!',
    ]
];

function __($key) {
    global $translations;
    $lang = $_SESSION['lang'];
    return $translations[$lang][$key] ?? $key;
}
?>