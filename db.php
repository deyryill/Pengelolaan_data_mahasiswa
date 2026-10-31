<?php
$host = 'localhost';
$dbname = 'db_mahasiswa';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        username VARCHAR(50) NOT NULL PRIMARY KEY,
        password VARCHAR(255) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS mahasiswa (
        nim VARCHAR(10) NOT NULL PRIMARY KEY,
        nama_lengkap VARCHAR(100) NOT NULL,
        program_studi VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $hashedPassword = password_hash('password', PASSWORD_DEFAULT);
        $insertAdmin = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $insertAdmin->execute(['admin', $hashedPassword]);
    }

} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>