<?php
require "koneksi.php";

if (isset($_GET['artikel_id'])) {
    $artikel_id = intval($_GET['artikel_id']);
    
    // Query for comments starting from the 6th comment
    $query_komentar = "SELECT k.*, u.username, u.foto_profil 
                      FROM komentar k 
                      JOIN users u ON k.user_id = u.id 
                      WHERE k.artikel_id = ? 
                      ORDER BY k.created_at DESC 
                      LIMIT 18446744073709551615 OFFSET 5";
    
    $stmt = $conn->prepare($query_komentar);
    $stmt->bind_param("i", $artikel_id);
    $stmt->execute();
    $result_komentar = $stmt->get_result();
    
    if ($result_komentar->num_rows > 0) {
        while ($komentar = $result_komentar->fetch_assoc()) {
        // Determine profile picture path
        $foto_profil = $komentar['foto_profil'];
        $foto_profil_path = !empty($foto_profil) ? 'css/image/profile/' . $foto_profil : 'https://bootdey.com/img/Content/avatar/avatar1.png';

        // Add debug logging if needed
        error_log("Loading comment image: " . $foto_profil_path);
            ?>
            <div class="komentar">
                <div class="komentar-profil">
                    <img src="<?php echo htmlspecialchars($foto_profil_path); ?>" alt="Foto Profil">
                    <span class="username"><?php echo htmlspecialchars($komentar['username']); ?></span>
                </div>
                <div class="komentar-isi">
                    <p><?php echo htmlspecialchars($komentar['isi']); ?></p>
                    <small><?php echo date('d M Y H:i', strtotime($komentar['created_at'])); ?></small>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>Tidak ada komentar tambahan.</p>";
    }
} else {
    echo "<p>ID Artikel tidak valid.</p>";
}
?>