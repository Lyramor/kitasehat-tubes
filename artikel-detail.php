<?php  
  require "inc/koneksi.php";

  // Mengambil nilai judul dari parameter GET
  $judul = htmlspecialchars($_GET['judul']); 

  // Mengeksekusi query SQL untuk mengambil data artikel berdasarkan judul
  $queryArtikel = mysqli_query($conn, "SELECT * FROM artikel WHERE judul='$judul'"); 
  
  // Mengambil hasil query dalam bentuk array
  $artikel = mysqli_fetch_array($queryArtikel); 
  
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- font awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- css -->
  <link rel="stylesheet" href="css/style3.css">
  <link rel="stylesheet" href="css/artikel.css">


  <title>kitasehat | Artikel</title>
</head>

<body>
  <!-- Navbar start -->
  <div class="navbar">
    <a href="index.php" class="navbar-logo">
      Kita<span>Sehat</span>.
    </a>

    <div class="search-box">
      <form action="artikel.php" method="get">
        <input type="text" name="search" id="srch" placeholder="search">
        <button type="submit"><i class="fa-solid fa-search"></i></button>
      </form>
    </div>

    <div class="navbar-nav">
      <a href="index.php">Beranda</a>
      <a href="index.php">About Me</a>
      <a href="index.php">Artikel</a>
      <a href="index.php">Kontak</a>
      <?php
      session_start();
      // Cek apakah sudah login berdasarkan session
      if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
          // Jika pengguna sudah login, tampilkan tombol Logout
          echo '<a href="logout.php" id="login">Logout</a>';
      } else {
          // Jika pengguna belum login, tampilkan tombol Masuk
          echo '<a href="login.php" id="login">Login</a>';
      }
      ?>
    </div>

    <div class="hamburger">
      <a href="#" id="hamburger" class="fa-solid fa-bars fa-xl"></a>
    </div>


  </div>
  <!-- Navbar end -->

  <!-- artikel section start-->
    <section id="detail" class="detail-artikel">
        <h3 style="margin-top: 2rem;"><?php echo $artikel['judul']; ?></h3>
    </section>

    <div class="container-main"></div>
    <div class="main">
      <p><?php echo $artikel['isi']; ?></p>
    </div>

    <div class="selebihnya">
        <a href="artikel.php">Artikel Lainnya</a>
    </div>
  <!-- artikel section end-->



  <!--footer Section start -->
  <section class="footer">
    <div class="box-container">
      <div class="box">
        <a href="#" class="navbar-logo">
          Kita<span>Sehat</span>.
        </a>
      </div>
      <div class="box">
        <h3>Quick Links</h3>
        <a href="#" class="link-footer">Beranda</a>
        <a href="#" class="link-footer">Layanan Kami</a>
        <a href="#" class="link-footer">Artikel</a>
        <a href="#" class="link-footer">Kontak</a>
      </div>
      <div class="box">
        <h3>Site Map</h3>
        <a href="#" class="link-footer">FAQ</a>
        <a href="#" class="link-footer">Blog</a>
        <a href="#" class="link-footer">Syarat & Ketentuan</a>
        <a href="#" class="link-footer">Kebijakan Privasi</a>
        <a href="#" class="link-footer">Karir</a>
        <a href="#" class="link-footer">Securty</a>
      </div>
      <div class="box">
        <h3>Social Media</h3>
        <a href="https://www.instagram.com/mmarsanj?igsh=MTN2MTM2YWZ3a3do" class="link-footer">Instagram</a>
        <a href="#" class="link-footer">Twitter</a>
        <a href="#" class="link-footer">Facebook</a>
      </div>
    </div>
  </section>
  <!-- Footer Section End -->

  <script src="js/script.js"></script>
</body>

</html>