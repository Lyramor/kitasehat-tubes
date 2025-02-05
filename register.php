<?php 
    require 'inc/koneksi.php';
    require 'inc/functions.php';

    if(isset($_POST["register"])) {
        if(register($_POST) > 0) {
            echo "<script>
                    alert('Registrasi berhasil! Silakan login.');
                    window.location.href = 'login.php';
                </script>";
?>                
            <meta http-equiv="refresh" content="0.5, url=login.php"/>
<?php  
        } else {
            echo mysqli_error($conn);
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
</body>
</html>