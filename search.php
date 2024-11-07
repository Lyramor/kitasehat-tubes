<?php
require "inc/koneksi.php";

if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];

    // Lakukan pencarian berdasarkan keyword
    $queryArtikel = mysqli_query($conn, "SELECT * FROM artikel WHERE judul LIKE '%$keyword%'");

    // Tampilkan hasil pencarian
    while($data = mysqli_fetch_array($queryArtikel)){
        echo '<div class="lainnya-page">';
        echo '<div class="lainnya-img">';
        echo '<img src="css/image/'.$data['gambar'].'" alt="">';
        echo '</div>';
        echo '<div class="content">';
        echo '<h3>'.$data['judul'].'</h3>';
        echo '<p>'.$data['sinopsis'].'</p>';
        echo '<a href="artikel-detail.php?judul='.$data['judul'].'">Baca Selengkapnya...</a>';
        echo '</div>';
        echo '</div>';
    }
}
?>
