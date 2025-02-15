<?php
session_start();
require 'inc/koneksi.php';
require 'inc/functions.php';

if (isset($_POST["register"])) {
    if (register($_POST) > 0) {
        $_SESSION["temp_user"] = [
            "username" => $_POST["username"],
            "nama_lengkap" => $_POST["nama_lengkap"],
            "email" => $_POST["email"],
            "phone" => $_POST["phone"], 
            "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
            "foto_profil" => "default.png"
        ];
        $_SESSION["success"] = "Registrasi berhasil! Silakan pilih peran Anda.";
    } else {
        $_SESSION["error"] = "Registrasi gagal! Silakan coba lagi.";
    }
}

// Jika role dipilih, simpan user ke database
if (isset($_POST["role"])) {
    if (!isset($_SESSION['temp_user'])) {
        $_SESSION["error"] = "Data registrasi tidak ditemukan!";
    } else {
        $data = $_SESSION['temp_user'];
        $role = $_POST["role"];

        // Insert ke database dengan nilai AUTO_INCREMENT untuk id
        $query = "INSERT INTO users (username, nama_lengkap, email, foto_profil, password, role, phone) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssss",
                $data['username'],
                $data['nama_lengkap'],
                $data['email'],
                $data['foto_profil'],
                $data['password'],
                $role,
                $data['phone']
            );

            if (mysqli_stmt_execute($stmt)) {
                unset($_SESSION['temp_user']); 
                $_SESSION["success_final"] = "Akun berhasil dibuat sebagai $role! Silakan login.";
                header("Location: login.php");
                exit;
            } else {
                $_SESSION["error"] = "Gagal menyimpan data: " . mysqli_error($conn);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION["error"] = "Gagal mempersiapkan query: " . mysqli_error($conn);
        }
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

    <!-- CSS -->
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
                        <label for="phone">Nomor HP</label>
                        <input type="tel" name="phone" id="phone" autocomplete="off" required pattern="[0-9]{10,15}" />
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
    if (isset($_SESSION["success"])) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Registrasi Berhasil!',
            text: '" . $_SESSION["success"] . "',
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
                document.getElementById('roleInput').value = role;
                document.getElementById('roleForm').submit();
            }
        });
        </script>";
        unset($_SESSION["success"]);
    }

    if (isset($_SESSION["success_final"])) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Registrasi Selesai!',
            text: '" . $_SESSION["success_final"] . "',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'login.php';
        });
        </script>";
        unset($_SESSION["success_final"]);
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

    <!-- Form hidden untuk mengirim role -->
    <form id="roleForm" method="post" action="" style="display: none;">
        <input type="hidden" name="role" id="roleInput">
    </form>

</body>

</html>