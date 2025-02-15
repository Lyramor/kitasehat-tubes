<?php 
  $conn = mysqli_connect("localhost", "root", "", "kitasehat");

  if(mysqli_connect_errno()){
    echo "Gagal koneksi ke database". mysqli_connect_error();
    exit();
  }

  if (!file_exists('upload/profile')) {
    mkdir('upload/profile', 0777, true);
  }

  // Debug session
var_dump($_SESSION);

// Debug parameter
var_dump($_GET);

// Debug artikel_id
if (isset($_GET['p'])) {
    $artikel_id = $_GET['p'];
    echo "ID Artikel: " . $artikel_id;
    
    // Debug query
    $query = "SELECT * FROM artikel WHERE id = '$artikel_id'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        echo "Error query: " . mysqli_error($conn);
    } else {
        $artikel = mysqli_fetch_assoc($result);
        var_dump($artikel);
    }
}
?>