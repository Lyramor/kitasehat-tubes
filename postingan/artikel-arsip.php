<?php
session_start();
require "../inc/koneksi.php";
require_once "auth.php"; 

// Authentication check
if(!isset($_SESSION['login'])){
    header("location: ../login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Handle unarchive
if(isset($_POST['unarchive'])) {
    $artikel_id = $_POST['artikel_id'];
    
    $updateQuery = mysqli_query($conn, "UPDATE artikel 
                                      SET status = 'aktif', 
                                          archived_at = NULL 
                                      WHERE id = '$artikel_id' 
                                      AND user_id = '$user_id'");
                                      
    if($updateQuery) {
        echo "<script>alert('Article successfully unarchived!'); window.location.href = 'artikel-arsip.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to unarchive article: " . mysqli_error($conn) . "');</script>";
    }
}

// Handle delete
if(isset($_POST['delete'])) {
    $artikel_id = $_POST['artikel_id'];
    
    // Get article data before deletion to get image filename
    $queryGetArtikel = mysqli_query($conn, "SELECT * FROM artikel WHERE id = '$artikel_id' AND user_id = '$user_id'");
    $artikel = mysqli_fetch_assoc($queryGetArtikel);
    
    if(!$artikel) {
        echo "<script>alert('Article not found!'); window.location.href = 'artikel-arsip.php';</script>";
        exit();
    }
    
    // Delete image if exists
    if(!empty($artikel['gambar'])) {
        $image_path = "../css/image/" . $artikel['gambar'];
        if(file_exists($image_path) && !unlink($image_path)) {
            echo "<script>alert('Failed to delete image!');</script>";
        }
    }
    
    // Delete article from database
    $deleteQuery = mysqli_query($conn, "DELETE FROM artikel WHERE id = '$artikel_id' AND user_id = '$user_id'");
    
    if($deleteQuery) {
        echo "<script>alert('Article successfully deleted!'); window.location.href = 'artikel-arsip.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to delete article: " . mysqli_error($conn) . "');</script>";
    }
}

// Fetch archived articles
$query = mysqli_query($conn, "SELECT artikel.*, kategori.nama AS nama_kategori 
                              FROM artikel 
                              JOIN kategori ON artikel.kategori_id = kategori.id 
                              WHERE artikel.user_id = '$user_id' 
                              AND artikel.status = 'arsip'
                              ORDER BY artikel.archived_at DESC");
$jumlahArtikel = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style2.css">
    <link rel="stylesheet" href="../css/artikel1.css">
    <link rel="stylesheet" href="../css/postingan.css">
    <title>Archived Articles - KitaSehat</title>
</head>

<body>
    <!-- Navbar start -->
    <div class="navbar" style="background-color: rgba(241, 241, 241);">
        <a href="#" class="navbar-logo">
            Kita<span>Sehat</span>.
        </a>
        <div class="navbar-nav">
            <a href="../index.php#beranda">Beranda</a>
            <a href="../index.php#layanan">About Us</a>
            <a href="../index.php#artikel">Artikel</a>
            <a href="../index.php#kontak">Kontak</a>
            <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'penulis'): ?>
                    <a href="./index.php">Postingan</a>
                <?php endif; ?>
                <a href="../profile.php" id="login">Profile</a>
            <?php else: ?>
                <a href="login.php" id="login">Login</a>
            <?php endif; ?>
        </div>
        <div class="hamburger">
            <a href="#" id="hamburger" class="fa-solid fa-bars fa-xl"></a>
        </div>
    </div>
    <!-- Navbar end -->

    <div class="container">
        <h2 style="margin-top: 6rem;">Arsip Artikel</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Sinopsis</th>
                        <th>Gambar</th>
                        <th>Dibuat pada</th>
                        <th>Arsip pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($jumlahArtikel == 0): ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada arsip artikel ditemukan.</td>
                        </tr>
                    <?php else: 
                        $nomor = 1;
                        while ($data = mysqli_fetch_array($query)): ?>
                            <tr>
                                <td><?php echo $nomor++; ?></td>
                                <td><?php echo $data['judul']; ?></td>
                                <td><?php echo $data['nama_kategori']; ?></td>
                                <td><?php echo $data['sinopsis']; ?></td>
                                <td>
                                    <?php if ($data['gambar']): ?>
                                        <img src="../css/image/<?php echo $data['gambar']; ?>" class="article-image">
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada gambar</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d M Y H:i', strtotime($data['created_at'])); ?></td>
                                <td><?php echo date('d M Y H:i', strtotime($data['archived_at'])); ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="artikel_id" value="<?php echo $data['id']; ?>">
                                        <button type="submit" name="unarchive" class="btn-warning btn-sm" onclick="return confirm('Are you sure you want to unarchive this article?')">
                                            Batal Arsip
                                        </button>
                                    </form>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="artikel_id" value="<?php echo $data['id']; ?>">
                                        <button type="submit" name="delete" class="btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this article?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile;
                    endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer Section -->
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
                <a href="https://www.instagram.com/mmarsanj" class="link-footer">Instagram</a>
                <a href="https://web.facebook.com/mmarsa.nj" class="link-footer">Facebook</a>
                <a href="#" class="link-footer">Twitter</a>
            </div>
        </div>
        <div class="create">
            <a href="https://www.instagram.com/mmarsanj" class="wm">
                Copyright@2023 | Created and Development by mmarsanj
            </a>
        </div>
    </section>
</body>
</html>