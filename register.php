<?php
session_start();
require 'inc/koneksi.php';
require 'inc/functions.php';

if (isset($_POST["register"])) {
    if (register($_POST) > 0) {
        header("Location: register.php");
        exit();
    } else {
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - KitaSehat</title>

    <!-- css -->
    <link rel="stylesheet" href="css/login4.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="main-container">
        <input type="checkbox" id="slide" />
        <div class="container">
            <div class="signup-container">
                <div class="text">Register</div>

                <form action="" method="post">
                    <div class="data">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" autofocus autocomplete="off" required />
                    </div>

                    <div class="data">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" autocomplete="off" required />
                    </div>

                    <div class="data">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" autocomplete="off" required />
                    </div>

                    <div class="data">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required />
                    </div>

                    <div class="data">
                        <label for="password1">Konfirmasi Password</label>
                        <input type="password" name="password1" id="password1" required />
                    </div>

                    <div class="btn-signup">
                        <button type="submit" name="register" id="register">Register</button>
                    </div>

                    <div class="signup-link">
                        Sudah punya akun?
                        <a href="login.php">Login</a>
                    </div>
                    <div class="signup-link">
                        <a href="index.php" style="text-decoration: none; color: black;">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert Notifikasi -->
    <?php
    // if (isset($_SESSION["success"])) {
    //     echo "<script>
    //     Swal.fire({
    //         icon: 'success',
    //         title: 'Registrasi Berhasil!',
    //         text: '" . $_SESSION["success"] . "',
    //         confirmButtonText: 'User'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             window.location.href = 'login.php';
    //         }
    //     });
    //     </script>";
    //     unset($_SESSION["success"]);
    // }

    // if (isset($_SESSION["error"])) {
    //     echo "<script>
    //     Swal.fire({
    //         icon: 'error',
    //         title: 'Registrasi Gagal!',
    //         text: '" . $_SESSION["error"] . "'
    //     });
    //     </script>";
    //     unset($_SESSION["error"]);
    // }

    if (isset($_SESSION["success"])) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Registrasi Berhasil!',
            text: 'Silakan pilih peran Anda.',
            showDenyButton: true,
            confirmButtonText: 'User',
            denyButtonText: 'Penulis'
        }).then((result) => {
            let role = '';
            if (result.isConfirmed) {
                role = 'user';
            } else if (result.isDenied) {
                role = 'penulis';
            }
    
            if (role !== '') {
                window.location.href = 'set_role.php?role=' + role;
            }
        });
        </script>";
        unset($_SESSION["success"]);
    }

    if (isset($_SESSION["error"])) {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Registrasi Gagal!',
            text: '" . $_SESSION["error"] . "'
        });
        </script>";
        unset($_SESSION["error"]);
    }
    ?>

</body>

</html>