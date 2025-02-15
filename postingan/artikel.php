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

// Get categories
$queryKategori = mysqli_query($conn, "SELECT * FROM kategori");

// Handle form submission
if (isset($_POST['simpan'])) {
    $judul = htmlspecialchars($_POST['judul']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $isi = $_POST['isi'];
    $sinopsis = htmlspecialchars($_POST['sinopsis']);
    $new_name = '';

    // Image Upload Handling
    if (!empty($_FILES["gambar"]["name"])) {
        $target_dir = "../css/image/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $nama_file = basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $image_size = $_FILES["gambar"]["size"];
        $random_name = bin2hex(random_bytes(10));
        $new_name = $random_name . "." . $imageFileType;

        $uploadOk = true;
        $error_message = "";

        // Check file size
        if ($image_size > 4000000) {
            $error_message = "File size must not exceed 4MB";
            $uploadOk = false;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $error_message = "Only JPG, JPEG, PNG & GIF files are allowed";
            $uploadOk = false;
        }

        // Upload file if everything is ok
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_dir . $new_name)) {
                // File uploaded successfully
            } else {
                $error_message = "Sorry, there was an error uploading your file.";
                $uploadOk = false;
            }
        }

        if (!$uploadOk) {
            echo "<div class='alert alert-warning'>$error_message</div>";
            $new_name = ''; 
        }
    }

    // Database insertion dengan status
    if (empty($error_message)) {
      $queryTambah = mysqli_query($conn, "INSERT INTO artikel 
                                        (kategori_id, user_id, judul, isi, sinopsis, gambar, status) 
                                        VALUES 
                                        ('$kategori', '$user_id', '$judul', '$isi', '$sinopsis', '$new_name', 'aktif')");

      if ($queryTambah) {
          echo '<div class="alert alert-success">Article saved successfully</div>';
          echo '<meta http-equiv="refresh" content="2">';
      } else {
          echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
      }
    }
}

// Fetch articles with active status only
$query = mysqli_query($conn, "SELECT artikel.*, kategori.nama AS nama_kategori 
                            FROM artikel 
                            JOIN kategori ON artikel.kategori_id = kategori.id 
                            WHERE artikel.user_id = '$user_id' 
                            AND artikel.status = 'aktif'
                            ORDER BY artikel.id DESC");
$jumlahArtikel = mysqli_num_rows($query);

  // Handle article deletion
  if(isset($_POST['delete'])) {
    $artikel_id = $_POST['artikel_id']; // Ambil ID dari form

    // Ambil data artikel sebelum dihapus untuk mendapatkan nama file gambar
    $queryGetArtikel = mysqli_query($conn, "SELECT * FROM artikel WHERE id = '$artikel_id' AND user_id = '$user_id'");
    $artikel = mysqli_fetch_assoc($queryGetArtikel);

    if(!$artikel) {
        echo "<script>alert('Artikel tidak ditemukan!'); window.location.href = 'artikel.php';</script>";
        exit();
    }

    // Hapus gambar jika ada
    if (!empty($artikel['gambar'])) {
        $image_path = "../css/image/" . $artikel['gambar'];
        if (file_exists($image_path) && !unlink($image_path)) {
            echo "<script>alert('Gagal menghapus gambar!');</script>";
        }
    }

    // Hapus artikel dari database
    $deleteQuery = mysqli_query($conn, "DELETE FROM artikel WHERE id = '$artikel_id' AND user_id = '$user_id'");

    if ($deleteQuery) {
        echo "<script>alert('Artikel berhasil dihapus!'); window.location.href = 'artikel.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus artikel: " . mysqli_error($conn) . "');</script>";
    }
  }


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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Trix Editor CSS & JS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>

    <!-- css -->
    <link rel="stylesheet" href="../css/style2.css">
    <link rel="stylesheet" href="../css/artikel1.css">
    <link rel="stylesheet" href="../css/postingan.css">
    <title>KitaSehat</title>
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
            <?php
                // Cek apakah sudah login berdasarkan session
                if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
                    // Periksa apakah ada role yang diset dalam session
                    if (isset($_SESSION['role']) && $_SESSION['role'] === 'penulis') {
                        echo '<a href="./index.php">Postingan</a>';
                    }
                    // Tampilkan tombol Logout jika sudah login
                    echo '<a href="../profile.php" id="login">Profile</a>';
                } else {
                    // Jika belum login, tampilkan tombol Login
                    echo '<a href="login.php" id="login">Login</a>';
                }
            ?>
        </div>
        <div class="hamburger">
            <a href="#" id="hamburger" style="margin-left: 1rem;" class="fa-solid fa-bars fa-xl"></a>
        </div>
    </div>
    <!-- Navbar end -->

    <!-- Form Section start -->
    <section id="#" class="postingan">
        <h2>Artikel Saya</h2>
    </section>
    <!-- tabel Artikel -->
    <div class="container">
    <h3 style="margin-bottom: 1rem;">Add New Article</h3>
        <form id="form-tambah-artikel" action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul">Title <span class="text-danger">*</span></label>
                <input type="text" id="judul" name="judul" required 
                        placeholder="Enter article title">
            </div>

            <div class="form-group">
                <label for="kategori">Category <span class="text-danger">*</span></label>
                <select name="kategori" id="kategori" required>
                    <option value="">Select One</option>
                    <?php while ($data = mysqli_fetch_array($queryKategori)) { ?>
                        <option value="<?php echo $data['id']; ?>"><?php echo $data['nama']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="isi">Content <span class="text-danger">*</span></label>
                <input id="article-content" type="hidden" name="isi">
                <trix-editor input="article-content" class="trix-content" placeholder="Write your article content here"></trix-editor>
            </div>

            <div class="form-group">
                <label for="sinopsis">Synopsis <span class="text-danger">*</span></label>
                <input id="article-synopsis" type="hidden" name="sinopsis">
                <trix-editor input="article-synopsis" class="trix-content" placeholder="Write a brief summary of your article"></trix-editor>
            </div>

            <div class="form-group">
                <label for="gambar">Image</label>
                <input type="file" id="gambar" name="gambar" 
                        accept="image/jpg,image/jpeg,image/png,image/gif"
                        onchange="previewImage(this)">
                <div id="imagePreview" class="image-preview"></div>
                <small class="text-muted">Accepted formats: JPG, PNG, GIF (max 4MB)</small>
            </div>

            <div class="form-group">
                <button class="btn-primary" type="submit" name="simpan">Save</button>
                <button class="btn-danger" type="button" onclick="window.print()">Print</button>
            </div>
        </form>
    </div>

    <div class="container">
        <h2 style="margin-top: 1rem;">My Articles</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Content</th>
                        <th>Synopsis</th>
                        <th>Image</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($jumlahArtikel == 0) { ?>
                        <tr><td colspan="8" class="text-center">No articles available</td></tr>
                    <?php } else {
                        $nomor = 1;
                        while ($data = mysqli_fetch_array($query)) { ?>
                            <tr>
                                <td><?php echo $nomor++; ?></td>
                                <td><?php echo $data['judul']; ?></td>
                                <td><?php echo $data['nama_kategori']; ?></td>
                                <td><?php echo substr($data['isi'], 0, 100) . '...'; ?></td>
                                <td><?php echo $data['sinopsis']; ?></td>
                                <td>
                                    <?php if ($data['gambar']) { ?>
                                        <img src="../css/image/<?php echo $data['gambar']; ?>" class="article-image">
                                    <?php } else { ?>
                                        <span class="text-muted">No image</span>
                                    <?php } ?>
                                </td>
                                <td class="timestamp"><?php echo $data['created_at']; ?></td>
                                <td>
                                    <a href="artikel-detail.php?p=<?php echo $data['id']; ?>" class="btn-info btn-sm">Show</a>
                                    <a href="artikel-edit.php?p=<?php echo $data['id']; ?>" class="btn-warning btn-sm">Edit</a>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="artikel_id" value="<?php echo $data['id']; ?>">
                                        <button type="submit" name="delete" class="btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- From Section end -->

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

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
    <script>
        // Form validation
        document.getElementById('form-tambah-artikel').onsubmit = function(e) {
            const title = document.getElementById('judul').value.trim();
            const category = document.getElementById('kategori').value;
            const content = document.getElementById('article-content').value.trim();
            const synopsis = document.getElementById('sinopsis').value.trim();
            
            if (!title || !category || !content || !synopsis) {
                alert('Please fill in all required fields');
                e.preventDefault();
                return false;
            }
            
            const image = document.getElementById('gambar').files[0];
            if (image && image.size > 4 * 1024 * 1024) {
                alert('Image size must not exceed 4MB');
                e.preventDefault();
                return false;
            }
            
            return true;
        };

  // Styles for Trix Editor
  document.head.insertAdjacentHTML('beforeend', `
  <style>
      trix-editor {
          min-height: 300px;
          max-height: 500px;
          overflow-y: auto;
          border: 1px solid #ddd;
          border-radius: 4px;
          padding: 10px;
          background-color: white;
          cursor: text;
      }
      
      trix-toolbar {
          background-color: #f8f9fa;
          padding: 5px;
          border: 1px solid #ddd;
          border-radius: 4px;
          margin-bottom: 5px;
      }

      trix-editor:focus {
          outline: none;
          border-color: #80bdff;
          box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
      }

      .trix-button-group {
          border: 1px solid #ddd;
          border-radius: 3px;
          margin-right: 5px;
      }

      .trix-button {
          background: #fff;
          border: none;
          color: #333;
          padding: 4px 8px;
          cursor: pointer;
      }

      .trix-button:hover {
          background: #f0f0f0;
      }

      .trix-button.trix-active {
          background: #e9ecef;
      }
  </style>
  `);

  // Initialize when DOM is fully loaded
  document.addEventListener("DOMContentLoaded", function() {
      // Initialize Trix editors
      document.querySelectorAll("trix-editor").forEach(editor => {
          editor.setAttribute("contenteditable", "true");
      });

      // Event listener for Trix initialization
      document.addEventListener("trix-initialize", function(event) {
          event.target.editor.element.setAttribute("contenteditable", "true");
      });

      // Event listener for content changes
      document.addEventListener("trix-change", function(event) {
          const element = event.target;
          const inputId = element.getAttribute("input");
          const hiddenInput = document.getElementById(inputId);
          
          if (hiddenInput) {
              hiddenInput.value = element.editor.getDocument().toString();
          }
          
          // Optional: Add validation visual feedback
          if (element.editor.getDocument().toString().trim() === "") {
              element.classList.add("is-invalid");
          } else {
              element.classList.remove("is-invalid");
          }
      });

      // Prevent file attachments (optional - remove if you want to allow file attachments)
      document.addEventListener("trix-file-accept", function(event) {
          event.preventDefault();
      });

      // Image preview functionality
      function previewImage(input) {
          const preview = document.getElementById('imagePreview');
          preview.innerHTML = '';
          
          if (input.files && input.files[0]) {
              const reader = new FileReader();
              
              reader.onload = function(e) {
                  const img = document.createElement('img');
                  img.src = e.target.result;
                  img.style.maxWidth = '100%';
                  img.style.height = 'auto';
                  preview.appendChild(img);
              }
              
              reader.readAsDataURL(input.files[0]);
          }
      }

      // Attach image preview handler
      const imageInput = document.getElementById('gambar');
      if (imageInput) {
          imageInput.addEventListener('change', function() {
              previewImage(this);
          });
      }

      // Form validation
      const form = document.getElementById('form-tambah-artikel');
      if (form) {
          form.addEventListener('submit', function(e) {
              const title = document.getElementById('judul').value.trim();
              const category = document.getElementById('kategori').value;
              const content = document.getElementById('article-content').value.trim();
              const synopsis = document.getElementById('article-synopsis').value.trim();
              
              let isValid = true;
              let errorMessage = [];

              // Validate title
              if (!title) {
                  errorMessage.push("Title is required");
                  isValid = false;
              }

              // Validate category
              if (!category) {
                  errorMessage.push("Category is required");
                  isValid = false;
              }

              // Validate content
              if (!content) {
                  errorMessage.push("Content is required");
                  isValid = false;
              }

              // Validate synopsis
              if (!synopsis) {
                  errorMessage.push("Synopsis is required");
                  isValid = false;
              }

              // Validate image if one is selected
              const image = document.getElementById('gambar').files[0];
              if (image) {
                  if (image.size > 4 * 1024 * 1024) {
                      errorMessage.push("Image size must not exceed 4MB");
                      isValid = false;
                  }

                  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                  if (!allowedTypes.includes(image.type)) {
                      errorMessage.push("Only JPG, JPEG, PNG & GIF files are allowed");
                      isValid = false;
                  }
              }

              if (!isValid) {
                  e.preventDefault();
                  alert(errorMessage.join("\n"));
                  return false;
              }

              return true;
          });
      }

      // Optional: Auto-save draft functionality
      let autoSaveTimeout;
      document.querySelectorAll("trix-editor").forEach(editor => {
          editor.addEventListener("trix-change", function() {
              clearTimeout(autoSaveTimeout);
              autoSaveTimeout = setTimeout(function() {
                  // Here you could implement auto-save functionality
                  console.log("Content auto-saved");
                  // Example: localStorage.setItem('draft-' + editor.getAttribute('input'), editor.editor.getDocument().toString());
              }, 1000);
          });
      });
  });
    </script>
</body>
</html>