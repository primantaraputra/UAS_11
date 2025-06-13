<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/koneksi.php';

$id = intval($_GET['id'] ?? 0);

// Ambil data barang berdasarkan ID
$barang = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang = $id");
$data = mysqli_fetch_assoc($barang);
if (!$data) {
    echo "Barang tidak ditemukan.";
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $kategori = intval($_POST['kategori']);
    $supplier = intval($_POST['supplier']);
    $stok = intval($_POST['stok']);
    $harga = intval($_POST['harga']);

    mysqli_query($koneksi, "UPDATE barang SET 
        nama_barang = '$nama',
        id_kategori = $kategori,
        id_supplier = $supplier,
        stok = $stok,
        harga = $harga
        WHERE id_barang = $id
    ");
    header("Location: index.php");
    exit;
}

// Ambil data kategori dan supplier untuk dropdown
$kategori_result = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
$supplier_result = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY nama_supplier ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Barang - Inventory DJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, rgb(239, 187, 137), rgb(76, 128, 231));
            background-size: cover;
            background-attachment: fixed;
            transition: all 0.3s ease;
            /* Penyesuaian Tailwind CSS untuk responsivitas - Bagian Body */
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
            color: black
        }
        .dark-mode select {
            color: black
        }
        /* Penyesuaian Tailwind CSS untuk tampilan Dark Mode input/select */
        .dark-mode input[type="text"],
        .dark-mode input[type="number"],
        .dark-mode select {
            background-color: #e5e7eb; /* Warna latar belakang yang lebih terang */
            color: #1f2937; /* Warna teks yang lebih gelap agar kontras */
        }
    </style>
</head>
<body>
    <header class="p-4 md:p-10 mx-auto shadow-lg sticky top-0 z-50 mb-3 backdrop-blur-md w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
            <h1 class="text-xl md:text-3xl font-bold mb-3 md:mb-0">Edit Barang</h1>
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
            &larr; Kembali ke Barang
        </a>

        <form method="POST" class="bg-white p-6 rounded-2xl shadow-md max-w-full md:max-w-xl mx-auto">
            <h2 class="text-xl font-semibold mb-4 text-center">Edit Data Barang</h2>

            <div class="mb-4">
                <label for="nama_barang" class="block mb-1">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" value="<?= $data['nama_barang'] ?>" class="w-full border border-gray-300 p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="kategori" class="block mb-1">Kategori</label>
                <select id="kategori" name="kategori" class="w-full border border-gray-300 p-2 rounded" required>
                    <?php 
                    // Pastikan kategori_result tidak kosong sebelum looping
                    if (mysqli_num_rows($kategori_result) > 0) {
                        mysqli_data_seek($kategori_result, 0); // Reset pointer jika sudah digunakan sebelumnya
                        while ($k = mysqli_fetch_assoc($kategori_result)) : 
                    ?>
                        <option value="<?= $k['id_kategori'] ?>" <?= $data['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
                            <?= $k['nama_kategori'] ?>
                        </option>
                    <?php 
                        endwhile; 
                    } else {
                        echo '<option value="">Tidak ada kategori tersedia</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="supplier" class="block mb-1">Supplier</label>
                <select id="supplier" name="supplier" class="w-full border border-gray-300 p-2 rounded" required>
                    <?php 
                    // Pastikan supplier_result tidak kosong sebelum looping
                    if (mysqli_num_rows($supplier_result) > 0) {
                        mysqli_data_seek($supplier_result, 0); // Reset pointer jika sudah digunakan sebelumnya
                        while ($s = mysqli_fetch_assoc($supplier_result)) : 
                    ?>
                        <option value="<?= $s['id_supplier'] ?>" <?= $data['id_supplier'] == $s['id_supplier'] ? 'selected' : '' ?>>
                            <?= $s['nama_supplier'] ?>
                        </option>
                    <?php 
                        endwhile; 
                    } else {
                        echo '<option value="">Tidak ada supplier tersedia</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="stok" class="block mb-1">Stok</label>
                <input type="number" id="stok" name="stok" value="<?= $data['stok'] ?>" class="w-full border border-gray-300 p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label for="harga" class="block mb-1">Harga</label>
                <input type="number" id="harga" name="harga" value="<?= $data['harga'] ?>" class="w-full border border-gray-300 p-2 rounded" required>
            </div>

            <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-2xl hover:bg-yellow-700 w-full">
                Simpan Perubahan
            </button>
        </form>
    </div>
</body>
</html>