<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $supplier = $_POST['supplier'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $sql = "INSERT INTO barang (nama_barang, id_kategori, id_supplier, stok, harga) VALUES ('$nama_barang', '$kategori', '$supplier', '$stok', '$harga')";
    mysqli_query($koneksi, $sql);
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang=$id");
}

$barang_result = mysqli_query($koneksi, "SELECT b.*, k.nama_kategori, s.nama_supplier FROM barang b 
JOIN kategori k ON b.id_kategori = k.id_kategori 
JOIN supplier s ON b.id_supplier = s.id_supplier");

$kategori_result = mysqli_query($koneksi, "SELECT * FROM kategori");
$supplier_result = mysqli_query($koneksi, "SELECT * FROM supplier");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Barang - Inventory DJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, rgb(239, 187, 137), rgb(76, 128, 231));
            background-size: cover;
            background-attachment: fixed;
            transition: all 0.3s ease;
            min-height: 100vh; /* Tambahkan ini agar body mengisi seluruh tinggi viewport */
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
        .dark-mode .bg-gray-200 {
            background-color: #374151;
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
        /* Menyesuaikan warna teks input/select pada dark mode agar terlihat */
        .dark-mode input[type="text"],
        .dark-mode input[type="number"],
        .dark-mode select {
            background-color: #e5e7eb; /* Warna latar belakang yang lebih terang */
            color: #1f2937; /* Warna teks yang lebih gelap agar kontras */
        }
        .dark-mode .bg-white th,
        .dark-mode .bg-white td {
            color: white; /* Untuk header dan sel tabel di dark mode */
        }
        .dark-mode .bg-white tr.text-center.border-t.border-gray-300 {
            background-color: #1f2937;
        }
    </style>
</head>
<body>
    <header class="p-4 md:p-10 mx-auto shadow-lg sticky top-0 z-50 mb-3 backdrop-blur-md w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
            <h1 class="text-xl md:text-3xl font-bold mb-3 md:mb-0">Kelola Barang</h1>
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
        <a href="../index.php" class="text-white bg-white/30 px-4 py-2 rounded-2xl inline-block mb-4 hover:bg-white/50 transition">&larr; Kembali ke Dashboard</a>

        <form method="POST" class="bg-white p-6 rounded-2xl shadow-md max-w-full md:max-w-xl mx-auto mb-10">
            <h2 class="text-xl font-semibold mb-4 text-center">Tambah Barang Baru</h2>
            <div class="mb-4">
                <label for="nama_barang" class="block mb-1">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="kategori" class="block mb-1">Kategori</label>
                <select id="kategori" name="kategori" class="w-full border border-gray-300 p-2 rounded" required>
                    <?php while ($row = mysqli_fetch_assoc($kategori_result)) : ?>
                        <option value="<?= $row['id_kategori'] ?>"><?= $row['nama_kategori'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="supplier" class="block mb-1">Supplier</label>
                <select id="supplier" name="supplier" class="w-full border border-gray-300 p-2 rounded" required>
                    <?php while ($row = mysqli_fetch_assoc($supplier_result)) : ?>
                        <option value="<?= $row['id_supplier'] ?>"><?= $row['nama_supplier'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="stok" class="block mb-1">Stok</label>
                <input type="number" id="stok" name="stok" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="harga" class="block mb-1">Harga</label>
                <input type="number" id="harga" name="harga" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-2xl hover:bg-blue-700 w-full">Tambah</button>
        </form>

        <h2 class="text-xl font-semibold mb-4 text-center">Daftar Barang</h2>
        <div class="shadow rounded-2xl overflow-x-auto">
            <table class="min-w-full bg-white text-sm">
                <thead class="bg-gray-200 text-center">
                    <tr>
                        <th class="border px-4 py-2">No</th>
                        <th class="border px-4 py-2">Nama Barang</th>
                        <th class="border px-4 py-2">Kategori</th>
                        <th class="border px-4 py-2">Supplier</th>
                        <th class="border px-4 py-2">Stok</th>
                        <th class="border px-4 py-2">Harga</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Pastikan barang_result tidak kosong sebelum looping
                    if (mysqli_num_rows($barang_result) > 0) {
                        mysqli_data_seek($barang_result, 0); // Reset pointer jika sudah digunakan sebelumnya
                        while ($row = mysqli_fetch_assoc($barang_result)) :
                    ?>
                        <tr class="text-center border-t border-gray-300">
                            <td class="px-4 py-2"><?= $no++ ?></td>
                            <td class="px-4 py-2"><?= $row['nama_barang'] ?></td>
                            <td class="px-4 py-2"><?= $row['nama_kategori'] ?></td>
                            <td class="px-4 py-2"><?= $row['nama_supplier'] ?></td>
                            <td class="px-4 py-2"><?= $row['stok'] ?></td>
                            <td class="px-4 py-2 text-right">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2 space-x-2 whitespace-nowrap">
                                <a href="edit.php?id=<?= $row['id_barang'] ?>" class="text-yellow-600 hover:underline">Edit</a>
                                <a href="?hapus=<?= $row['id_barang'] ?>" onclick="return confirm('Hapus barang ini?')" class="text-red-600 hover:underline">Hapus</a>
                            </td>
                        </tr>
                    <?php 
                        endwhile; 
                    } else {
                        echo '<tr class="text-center border-t border-gray-300"><td colspan="7" class="px-4 py-2">Tidak ada data barang.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>