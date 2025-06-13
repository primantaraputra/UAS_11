<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/koneksi.php';

$error = '';
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Ambil data supplier sesuai id
$result = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_supplier = $id");
$supplier = mysqli_fetch_assoc($result);
if (!$supplier) {
    header("Location: index.php");
    exit;
}

// Handle Edit Supplier
if (isset($_POST['edit'])) {
    $nama = trim($_POST['nama_supplier']);
    if ($nama) {
        $nama = mysqli_real_escape_string($koneksi, $nama);
        $cek = mysqli_query($koneksi, "SELECT * FROM supplier WHERE nama_supplier='$nama' AND id_supplier != $id");
        if (mysqli_num_rows($cek) === 0) {
            mysqli_query($koneksi, "UPDATE supplier SET nama_supplier='$nama' WHERE id_supplier=$id");
            header("Location: index.php");
            exit;
        } else {
            $error = "Nama supplier sudah digunakan supplier lain!";
        }
    } else {
        $error = "Nama supplier tidak boleh kosong.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Supplier - Inventory DJ</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
    body {
        background: linear-gradient(135deg, rgb(239, 187, 137), rgb(76, 128, 231));
        background-size: cover;
        background-attachment: fixed;
        transition: all 0.3s ease;
        /* Penyesuaian Tailwind CSS untuk responsivitas - Bagian Body (sama seperti kategori/edit.php) */
        min-height: 100vh; /* Pastikan body mengisi seluruh tinggi viewport */
        display: flex; /* Gunakan flexbox untuk layout */
        flex-direction: column; /* Susun item secara kolom */
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
    .dark-mode select {
        color: black; /* Meskipun tidak ada select di sini, konsisten dengan file lain */
    }
    /* Penyesuaian Tailwind CSS untuk tampilan Dark Mode input/select (sama seperti kategori/edit.php) */
    .dark-mode input[type="text"],
    .dark-mode input[type="number"], /* Ditambahkan jika ada input type number */
    .dark-mode select { /* Ditambahkan jika ada select */
        background-color: #e5e7eb; /* Warna latar belakang yang lebih terang */
        color: #1f2937; /* Warna teks yang lebih gelap agar kontras */
    }
</style>
</head>
<body>
    <header class="p-4 md:p-10 mx-auto shadow-lg sticky top-0 z-50 mb-3 backdrop-blur-md w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
            <h1 class="text-xl md:text-3xl font-bold mb-3 md:mb-0">Edit Supplier</h1>
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
            &larr; Kembali ke Daftar Supplier
        </a>

        <form method="POST" class="bg-white p-6 rounded-2xl shadow-md max-w-full md:max-w-xl mx-auto">
            <h2 class="text-xl font-semibold mb-4 text-center">Edit Data Supplier</h2>

            <?php if ($error): ?>
                <p class="mb-4 text-red-600 font-medium text-center max-w-full md:max-w-xl mx-auto md:mx-0"><?= $error ?></p>
            <?php endif; ?>

            <div class="mb-4">
                <label for="nama_supplier" class="block mb-1">Nama Supplier</label>
                <input type="text" id="nama_supplier" name="nama_supplier" value="<?= $supplier['nama_supplier'] ?>" required class="w-full border border-gray-300 p-2 rounded" />
            </div>

            <button type="submit" name="edit" class="bg-yellow-600 text-white px-4 py-2 rounded-2xl hover:bg-yellow-700 w-full">
                Simpan Perubahan
            </button>
        </form>
    </div>
</body>
</html>