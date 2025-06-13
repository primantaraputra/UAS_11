<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/koneksi.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$result = mysqli_query($koneksi, "SELECT * FROM kategori WHERE id_kategori = $id");
$kategori = mysqli_fetch_assoc($result);
if (!$kategori) {
    echo "Kategori tidak ditemukan.";
    exit;
}

$error = '';
if (isset($_POST['edit'])) {
    $nama = trim($_POST['nama_kategori']);
    if ($nama) {
        $nama = mysqli_real_escape_string($koneksi, $nama);
        $cek = mysqli_query($koneksi, "SELECT * FROM kategori WHERE nama_kategori='$nama' AND id_kategori != $id");
        if (mysqli_num_rows($cek) === 0) {
            mysqli_query($koneksi, "UPDATE kategori SET nama_kategori='$nama' WHERE id_kategori=$id");
            header("Location: index.php");
            exit;
        } else {
            $error = "Nama kategori sudah digunakan.";
        }
    } else {
        $error = "Nama kategori tidak boleh kosong.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Kategori - Inventory DJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, rgb(239, 187, 137), rgb(76, 128, 231));
            background-size: cover;
            background-attachment: fixed;
            /* Penyesuaian Tailwind CSS untuk responsivitas - Bagian Body */
            min-height: 100vh; /* Pastikan body mengisi seluruh tinggi viewport */
            display: flex; /* Gunakan flexbox untuk layout */
            flex-direction: column; /* Susun item secara kolom */
            transition: all 0.3s ease;
        }
        .dark-mode {
            background: linear-gradient(135deg, rgb(114, 61, 12), rgb(11, 36, 88));
            color: white;
        }
        .dark-mode .bg-white {
            background-color: #1f2937;
        }
        .dark-mode .border-gray-300 {
            border-color: #4b5563;
        }
        .dark-mode input {
            color: black;
        }
    </style>
</head>
<body>
    <header class="p-4 md:p-10 mx-auto shadow-lg sticky top-0 z-50 mb-3 backdrop-blur-md w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
            <h1 class="text-xl md:text-3xl font-bold mb-3 md:mb-0">Edit Kategori</h1>
            <button onclick="toggleMode()" class="bg-white/30 text-white px-4 py-2 rounded-full hover:bg-white/50 transition">
                🌙 Ubah Mode
            </button>
        </div>
        <script>
            function toggleMode() {
                document.body.classList.toggle("dark-mode");
            }
        </script>
    </header>

    <div class="flex-grow p-4 md:p-10 w-full max-w-6xl mx-auto">
        <a href="index.php" class="text-white bg-white/30 px-4 py-2 rounded-2xl inline-block mb-4 hover:bg-white/50 transition">
            &larr; Kembali ke Kategori
        </a>

        <form method="POST" class="bg-white p-6 rounded-2xl shadow-md max-w-full md:max-w-xl mx-auto">
            <h2 class="text-xl font-semibold mb-4 text-center">Edit Data Kategori</h2>

            <?php if ($error): ?>
                <p class="mb-4 text-red-600 font-medium text-center max-w-full md:max-w-xl mx-auto md:mx-0"><?= $error ?></p>
            <?php endif; ?>

            <div class="mb-4">
                <label for="nama_kategori" class="block mb-1">Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" value="<?= $kategori['nama_kategori'] ?>" class="w-full border border-gray-300 p-2 rounded" required>
            </div>

            <button type="submit" name="edit" class="bg-yellow-600 text-white px-4 py-2 rounded-2xl hover:bg-yellow-700 w-full">
                Simpan Perubahan
            </button>
        </form>
    </div>
</body>
</html>