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
    // Ambil user_id dari database berdasarkan username yang login
    $username = $_SESSION['username'];
    $judul_artikel = $_POST['judul']; 
    
    $stmt_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt_user->bind_param("s", $username);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    
    if ($result_user->num_rows === 0) {
        $_SESSION['error'] = "User tidak ditemukan";
        header("Location: ../artikel-detail.php?judul=" . urlencode($judul_artikel) . "#komentar");
        exit();
    }
    
    $user = $result_user->fetch_assoc();
    $user_id = $user['id'];
    
    // Validasi komentar
    $isi_komentar = trim($_POST['isi_komentar']);
    $artikel_id = $_POST['artikel_id'];
    
    if (empty($isi_komentar)) {
        $_SESSION['error'] = "Komentar tidak boleh kosong";
        header("Location: ../artikel-detail.php?judul=" . urlencode($judul_artikel) . "#komentar");
        exit();
    }
    
    // Gunakan prepared statement untuk insert
    $stmt = $conn->prepare("INSERT INTO komentar (user_id, artikel_id, isi) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $artikel_id, $isi_komentar);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Komentar berhasil ditambahkan";
    } else {
        $_SESSION['error'] = "Gagal menambahkan komentar: " . $stmt->error;
    }
    
    header("Location: ../artikel-detail.php?judul=" . urlencode($judul_artikel) . "#komentar");
    exit();
}

// Jika bukan method POST, redirect ke halaman utama
header("Location: ../index.php");
exit();
?>