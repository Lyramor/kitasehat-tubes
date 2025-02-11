<?php
require "koneksi.php";

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function register($data)
{
    global $conn;

    // Validasi dan pembersihan input
    $username = mysqli_real_escape_string($conn, strtolower(trim($data["username"])));
    $nama_lengkap = mysqli_real_escape_string($conn, trim($data["nama_lengkap"]));
    $email = mysqli_real_escape_string($conn, strtolower(trim($data["email"])));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password1 = mysqli_real_escape_string($conn, $data["password1"]);

    // Validasi username
    // if (empty($username)) {
    //     echo "<script>alert('Username tidak boleh kosong!');</script>";
    //     return false;
    // }
    // if (strlen($username) < 3) {
    //     echo "<script>alert('Username minimal 3 karakter!');</script>";
    //     return false;
    // }
    // if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
    //     echo "<script>alert('Username hanya boleh mengandung huruf dan angka!');</script>";
    //     return false;
    // }

    if (empty($username)) {
        $_SESSION["error"] = "Username tidak boleh kosong!";
        return false;
    }
    if (strlen($username) < 3) {
        $_SESSION["error"] = "Username minimal 3 karakter!";
        return false;
    }
    if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        $_SESSION["error"] = "Username hanya boleh mengandung huruf dan angka!";
        return false;
    }

    // Validasi nama lengkap
    // if (empty($nama_lengkap)) {
    //     echo "<script>alert('Nama lengkap tidak boleh kosong!');</script>";
    //     return false;
    // }

    if (empty($nama_lengkap)) {
        $_SESSION["error"] = "Nama lengkap tidak boleh kosong!";
        return false;
    }

    // Validasi email
    // if (empty($email)) {
    //     echo "<script>alert('Email tidak boleh kosong!');</script>";
    //     return false;
    // }
    // if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //     echo "<script>alert('Format email tidak valid!');</script>";
    //     return false;
    // }

    if (empty($email)) {
        $_SESSION["error"] = "Email tidak boleh kosong!";
        return false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error"] = "Format email tidak valid!";
        return false;
    }

    // Cek apakah username sudah ada
    $stmt = mysqli_prepare($conn, "SELECT username FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // echo "<script>alert('Username sudah terdaftar!');</script>";
        $_SESSION["error"] = "Username sudah terdaftar!";
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        return false;
    }
    mysqli_stmt_free_result($stmt);
    mysqli_stmt_close($stmt);

    // Cek apakah email sudah ada
    $stmt = mysqli_prepare($conn, "SELECT email FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // echo "<script>alert('Email sudah terdaftar!');</script>";
        $_SESSION["error"] = "Email sudah terdaftar!";
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        return false;
    }
    mysqli_stmt_free_result($stmt);
    mysqli_stmt_close($stmt);

    // Validasi password
    if (empty($password)) {
        // echo "<script>alert('Password tidak boleh kosong!');</script>";
        $_SESSION["error"] = "Password tidak boleh kosong!";
        return false;
    }
    if (strlen($password) < 6) {
        // echo "<script>alert('Password minimal 6 karakter!');</script>";
        $_SESSION["error"] = "Password minimal 6 karakter!";
        return false;
    }
    if ($password !== $password1) {
        // echo "<script>alert('Konfirmasi password tidak sesuai!');</script>";
        $_SESSION["error"] = "Konfirmasi password tidak sesuai!";
        return false;
    }

    // Enkripsi password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Set default foto profil jika diperlukan
    $foto_profil = 'default.jpg'; // Sesuaikan dengan nama file default Anda

    // Insert user baru ke database
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, nama_lengkap, email, foto_profil, password, role) VALUES (?, ?, ?, ?, ?, 'user')");
    mysqli_stmt_bind_param($stmt, "sssss", $username, $nama_lengkap, $email, $foto_profil, $hashedPassword);

    // Cek apakah query INSERT berhasil
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["success"] = "Registrasi berhasil! Silakan login dengan akun Anda.";
        mysqli_stmt_close($stmt);
        return mysqli_affected_rows($conn); // Mengembalikan jumlah baris yang terpengaruh
    } else {
        $_SESSION["error"] = "Terjadi kesalahan saat pendaftaran!";
        mysqli_stmt_close($stmt);
        return false;
    }
}

// if (isset($_POST["register"])) {
//     if (register($_POST) > 0) {
//         // Use sweetalert for a more modern notification
//         echo "
//         <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
//         <script>
//         Swal.fire({
//             icon: 'success',
//             title: 'Registrasi Berhasil!',
//             text: 'Silakan login dengan akun Anda',
//             confirmButtonText: 'Login Sekarang'
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 window.location.href = 'login.php';
//             }
//         });
//         </script>";
//         exit();
//     } else {
//         echo "
//         <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
//         <script>
//         Swal.fire({
//             icon: 'error',
//             title: 'Registrasi Gagal',
//             text: 'Silakan coba lagi'
//         });
//         </script>";
//     }
// }
