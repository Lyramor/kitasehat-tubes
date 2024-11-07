<?php
// Periksa apakah pengguna sudah login
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    // Jika pengguna belum login, arahkan mereka ke halaman login
    header("Location: loginpage.php");
    exit();
}

// Periksa peran/otorisasi pengguna jika diperlukan
// Misalnya, jika hanya admin yang diizinkan mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    // Jika pengguna bukan admin, arahkan mereka ke halaman lain atau tampilkan pesan kesalahan
    header("Location: unauthorized.php"); // Ganti dengan halaman yang sesuai
    exit();
}
?>