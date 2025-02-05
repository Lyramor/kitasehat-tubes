<?php
session_start();
require "koneksi.php";

// Periksa apakah user sudah login
if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu";
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt_user->bind_param("s", $_SESSION['username']);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    
    if ($result_user->num_rows === 0) {
        $_SESSION['error'] = "User tidak ditemukan";
        header("Location: ../index.php#testimoni");
        exit();
    }
    
    $user = $result_user->fetch_assoc();
    $user_id = $user['id'];
    
    // Validasi testimoni
    $testimoni = trim($_POST['testimoni']);
    if (empty($testimoni)) {
        $_SESSION['error'] = "Testimoni tidak boleh kosong";
        header("Location: ../index.php#testimoni");
        exit();
    }
    
    // Gunakan prepared statement untuk insert
    $stmt = $conn->prepare("INSERT INTO testimoni (user_id, isi) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $testimoni);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Terima kasih atas testimoni Anda!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan testimoni: " . $stmt->error;
    }
    
    header("Location: ../index.php#testimoni");
    exit();
}

// Jika bukan method POST, redirect ke halaman utama
header("Location: ../index.php");
exit();
?>