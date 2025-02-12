<?php
session_start();
require 'inc/koneksi.php';

if (isset($_GET["role"])) {
    $role = $_GET["role"];

    if (!isset($_SESSION['temp_user'])) {
        $_SESSION["error"] = "Data registrasi tidak ditemukan!";
        header("Location: register.php");
        exit();
    }

    $data = $_SESSION['temp_user'];

    // Insert user dengan role yang dipilih
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, nama_lengkap, email, foto_profil, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param(
        $stmt,
        "ssssss",
        $data['username'],
        $data['nama_lengkap'],
        $data['email'],
        $data['foto_profil'],
        $data['password'], // Password sudah di-hash di register.php
        $role
    );

    if (mysqli_stmt_execute($stmt)) {
        unset($_SESSION['temp_user']); // Hapus data sementara
        $_SESSION["success"] = "Akun berhasil dibuat! Silakan login.";
    } else {
        $_SESSION["error"] = "Gagal membuat akun!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <script>
        <?php if (isset($_SESSION["success"])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Registrasi Berhasil!',
                text: '<?php echo $_SESSION["success"]; ?>',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = "login.php"; // Redirect setelah OK
            });
            <?php unset($_SESSION["success"]); // Hapus session agar tidak muncul lagi 
            ?>
        <?php endif; ?>
    </script>

</body>

</html>