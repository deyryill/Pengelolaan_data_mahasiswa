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
        'register_title' => 'Daftar Akun Baru',
        'username' => 'Username',
        'password' => 'Password',
        'login_btn' => 'Login',
        'register_btn' => 'Daftar',
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
        'success_register' => 'Registrasi berhasil! Silakan login.',
        'success_profile' => 'Profil berhasil diperbarui!',
        'err_empty' => 'Semua kolom wajib diisi!',
        'err_login' => 'Username atau password salah!',
        'err_username_exist' => 'Username sudah digunakan!',
        'err_current_password' => 'Password saat ini salah!',
        'confirm_delete' => 'Apakah Anda yakin ingin menghapus data ini?',
        'profile' => 'Profil Saya',
        'profile_title' => 'Pengaturan Profil',
        'current_password' => 'Password Saat Ini (Wajib)',
        'new_username' => 'Username Baru',
        'new_password' => 'Password Baru (Opsional)',
        'have_account' => 'Sudah punya akun? Login di sini',
        'no_account' => 'Belum punya akun? Daftar di sini'
    ],
    'en' => [
        'app_title' => 'Student Management System',
        'login_title' => 'Please Login',
        'register_title' => 'Register New Account',
        'username' => 'Username',
        'password' => 'Password',
        'login_btn' => 'Login',
        'register_btn' => 'Register',
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
        'success_register' => 'Registration successful! Please login.',
        'success_profile' => 'Profile updated successfully!',
        'err_empty' => 'All fields are required!',
        'err_login' => 'Invalid username or password!',
        'err_username_exist' => 'Username already taken!',
        'err_current_password' => 'Current password is incorrect!',
        'confirm_delete' => 'Are you sure you want to delete this data?',
        'profile' => 'My Profile',
        'profile_title' => 'Profile Settings',
        'current_password' => 'Current Password (Required)',
        'new_username' => 'New Username',
        'new_password' => 'New Password (Optional)',
        'have_account' => 'Already have an account? Login here',
        'no_account' => "Don't have an account? Register here"
    ]
];

function __($key) {
    global $translations;
    $lang = $_SESSION['lang'];
    return $translations[$lang][$key] ?? $key;
}
?>