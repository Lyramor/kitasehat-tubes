<?php  
    session_start();
    require "inc/koneksi.php";

    $queryArtikel = mysqli_query($conn, "SELECT id, judul, gambar, sinopsis FROM artikel LIMIT 4")
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
    <title>KitaSehat</title>
</head>

<body>
    <!-- Navbar start -->
    <div class="navbar">
        <a href="#" class="navbar-logo">
            Kita<span>Sehat</span>.
        </a>

        <div class="navbar-nav">
            <a href="#beranda">Beranda</a>
            <a href="#layanan">About Us</a>
            <a href="#artikel">Artikel</a>
            <a href="#kontak">Kontak</a>
            <?php   
                if (isset($_SESSION['username'])) {
                    // Jika pengguna sudah login, tampilkan tombol Logout
                    echo '<a href="logout.php" id="login">Logout</a>';
                } else {
                    // Jika pengguna belum login, tampilkan tombol Masuk
                    echo '<a href="login.php" id="login">Login</a>';
                }
            ?>


        </div>


        <div class="hamburger">
            <a href="#" id="hamburger" style="margin-left: 1rem;" class="fa-solid fa-bars fa-xl"></a>
        </div>


    </div>
    <!-- Navbar end -->

    <!-- Hero Section Start -->
    <section class="hero" id="beranda">
        <main class="content">
            <h1>Artikel <span>Kesehatan</span></h1>
            <?php if (isset($_SESSION['username'])) { ?>
                <p>Halo <?php echo $_SESSION['username']; ?>, Ayo chat dokter dan update informasi seputar kesehatan.</p>
            <?php } else { ?>
            <p>Chat dokter dan update informasi seputar kesehatan.</p>
            <?php } ?>
            <a href="register.php" class="cta">Registrasi</a>
        </main>
    </section>
    <!-- Hero Section End -->

    <!-- Abaout Section Start -->
    <section id="layanan" class="layanan">
        <h2><span>About</span> Us</h2>
    </section>

    <div class="tentang">
        <div class="tentang-kami">
            <p>Selamat datang di Website Kesehatan!</p>
            <p> ini didedikasikan untuk menyediakan artikel-artikel kesehatan yang dapat membantu Anda
                menjaga
                kesehatan dan kebugaran tubuh. Dalam artikel-artikel ini, Anda akan menemukan berbagai informasi terkait
                kesehatan, gaya hidup sehat, tips dan trik, serta berita terkini dalam dunia kesehatan.</p>
            <p>Kami berkomitmen untuk menyajikan konten yang akurat dan terpercaya. Artikel-artikel
                kami disusun
                berdasarkan penelitian yang terkini dan dengan dukungan tenaga ahli di bidang kesehatan. Kami juga
                mengundang Anda untuk berpartisipasi dalam komunitas kami dengan memberikan komentar, berbagi
                pengalaman,
                dan bertanya tentang topik-topik kesehatan yang Anda minati.</p>
            <p>Terima kasih telah mengunjungi Website Kesehatan. Semoga artikel-artikel yang kami
                sajikan dapat
                memberikan
                manfaat dan inspirasi untuk hidup sehat. Jaga kesehatan Anda dan selamat membaca!
            </p>
        </div>
    </div>
    <!-- About Section End -->

    <!-- Artikel Section Start -->
    <section id="artikel" class="artikel">
        <h2>Artikel</h2>
    </section>

    <div class="lainnya">
        <?php while($data = mysqli_fetch_array($queryArtikel)){ ?>
        <div class="lainnya-page">
            <div class="lainnya-img">
                <img src="css/image/<?php echo $data['gambar']; ?>" alt="">
            </div>
            <div class="content">
                <h3><?php echo $data['judul']; ?></h3>
                <p><?php echo $data['sinopsis']; ?></p>
                <a href="artikel-detail.php?judul=<?php echo $data['judul']; ?>">Baca Selengkapnya...</a>
            </div>
        </div>
        <?php } ?>
    </div>
    <div class="selebihnya">
        <a href="artikel.php">Artikel lainnya</a>
    </div>
    <!-- Artikel Section End -->

    <!-- Kontak Section Start -->
    <section id="kontak" class="kontak">
        <h2>Kontak</h2>
    </section>

    <div class="row">
        <div class="doctor">
            <div class="doctor-img">
                <img src="css/image/doctor1.jpg" alt="">
            </div>
            <h3>Dr. Muhammad Ali</h3>
            <p>Dokter Umum</p>
            <a href="http://wa.me/6285871416346">Hubungi Sekarang</a>
        </div>
        <div class="doctor">
            <div class="doctor-img">
                <img src="css/image/doctor2.jpg" alt="">
            </div>
            <h3>Dr. Masduki</h3>
            <p>Dokter Ahli Psikologi</p>
            <a href="http://wa.me/6285871416346">Hubungi Sekarang</a>
        </div>
        <div class="doctor">
            <div class="doctor-img">
                <img src="css/image/doctor3.jpg" alt="">
            </div>
            <h3>Dr. Rahman</h3>
            <p>Dokter Ahli Gizi</p>
            <a href="http://wa.me/6285871416346">Hubungi Sekarang</a>
        </div>
        <div class="doctor">
            <div class="doctor-img">
                <img src="css/image/doctor4.jpg" alt="">
            </div>
            <h3>Dr. Siti</h3>
            <p>Dokter Ahli Gigi</p>
            <a href="http://wa.me/6285871416346">Hubungi Sekarang</a>
        </div>
        <div class="doctor">
            <div class="doctor-img">
                <img src="css/image/doctor5.jpg" alt="">
            </div>
            <h3>Dr. Nur</h3>
            <p>Dokter Ahli Bedah</p>
            <a href="http://wa.me/6285871416346">Hubungi Sekarang</a>
        </div>
    </div>
    <!-- Kontak Section End -->

    <!-- footer Section start -->
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
                <a href="https://web.facebook.com/mmarsa.nj" class="link-footer">Facebook</a>                
                <a href="#" class="link-footer">Twitter</a>
            </div>
        </div>
        <div class="create">
            <a href="https://www.instagram.com/mmarsanj?igsh=MTN2MTM2YWZ3a3do" class="wm">
                Copyright@2023 | Created and Development by mmarsanj
            </a>
        </div>
    </section>
    <!-- Footer Section End -->

    <script src="js/script.js"></script>
</body>

</html>