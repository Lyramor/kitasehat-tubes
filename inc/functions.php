<?php 
require "koneksi.php";

function query($query){
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)){
        $rows [] = $row;
    }
    return $rows;
}

function register($data) {
    global $conn;

    $username = strtolower(stripcslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password1 = mysqli_real_escape_string($conn, $data["password1"]);

// cek username duplicate
$result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");

if( mysqli_fetch_assoc($result)) {
    echo "<script>
        alert('Username sudah terdaftar!');
    </script>";
    return false;
}

// cek konfirmasi password
if( $password !== $password1 ) {
    echo "<script>
        alert('Konfirmasi password tidak sesuai!');
    </script>";
    return false;
}

// enskripsi password
$password = password_hash($password, PASSWORD_DEFAULT);

// tambah userbaru ke database
mysqli_query($conn, "INSERT INTO users VALUES(null, '$username', '$password', 'user')");

return mysqli_affected_rows($conn);
}

?>