<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/koneksi.php';

$error = '';

// Ambil semua barang
$barangResult = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang ASC");

// Handle Tambah Stok Masuk
if (isset($_POST['tambah'])) {
    $id_barang = intval($_POST['id_barang']);
    $jumlah = intval($_POST['jumlah']);
    $keterangan = mysqli_real_escape_string($koneksi, trim($_POST['keterangan'] ?? ''));

    if ($id_barang > 0 && $jumlah > 0) {
        // Tambah ke stok_masuk
        mysqli_query($koneksi, "INSERT INTO stok_masuk (id_barang, jumlah, tanggal, keterangan) VALUES ($id_barang, $jumlah, CURDATE(), '$keterangan')");

        // Update stok di tabel barang
        mysqli_query($koneksi, "UPDATE barang SET stok = stok + $jumlah WHERE id_barang = $id_barang");

        header("Location: index.php");
        exit;
    } else {
        $error = "Barang dan jumlah harus valid dan lebih dari nol.";
    }
}

// Ambil data stok masuk untuk ditampilkan
$stokMasukResult = mysqli_query($koneksi, "
    SELECT sm.*, b.nama_barang 
    FROM stok_masuk sm
    JOIN barang b ON sm.id_barang = b.id_barang 
    ORDER BY sm.tanggal ASC, sm.id_stok_masuk ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Stok Masuk - Inventory JEDI</title>
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
        .dark-mode .bg-gray-200 {
            background-color: #374151;
        }
        .dark-mode .border-gray-300 {
            border-color: #4b5563;
        }
        .dark-mode input, 
        .dark-mode select {
            color: black;
        }
    </style>
</head>
<body>
    <header class="p-4 md:p-10 mx-auto shadow-lg sticky top-0 z-50 mb-3 backdrop-blur-md w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
            <h1 class="text-xl md:text-3xl font-bold mb-3 md:mb-0">Stok Masuk</h1>
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

        <?php if ($error): ?>
            <p class="text-red-200 bg-red-500/30 px-4 py-2 max-w-full md:max-w-xl rounded mb-4 text-sm mx-auto md:mx-0"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" class="bg-white p-6 rounded-2xl shadow-md max-w-full md:max-w-xl mx-auto mb-10">
            <h2 class="text-xl font-semibold mb-4 text-center">Tambah Stok Masuk</h2>
            <div class="mb-4">
                <label for="id_barang" class="block mb-1">Pilih Barang</label>
                <select id="id_barang" name="id_barang" required class="w-full border border-gray-300 p-2 rounded">
                    <option value="">-- Pilih Barang --</option>
                    <?php while ($row = mysqli_fetch_assoc($barangResult)): ?>
                        <option value="<?= $row['id_barang'] ?>">
                            <?= htmlspecialchars($row['nama_barang']) ?> (Stok: <?= $row['stok'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="jumlah" class="block mb-1">Jumlah</label>
                <input type="number" id="jumlah" name="jumlah" min="1" required class="w-full border border-gray-300 p-2 rounded" />
            </div>
            <div class="mb-4">
                <label for="keterangan" class="block mb-1">Keterangan (opsional)</label>
                <input type="text" id="keterangan" name="keterangan" class="w-full border border-gray-300 p-2 rounded" />
            </div>
            <button type="submit" name="tambah" class="bg-green-600 text-white px-4 py-2 rounded-2xl hover:bg-green-700 w-full">Tambah</button>
        </form>

        <h2 class="text-xl font-semibold mb-4 text-center">Riwayat Stok Masuk</h2>
        <div class="shadow rounded-2xl overflow-hidden overflow-x-auto">
            <table class="min-w-full bg-white text-sm">
                <thead class="bg-gray-200 text-center">
                    <tr>
                        <th class="border px-4 py-2">Tanggal</th>
                        <th class="border px-4 py-2">Nama Barang</th>
                        <th class="border px-4 py-2">Jumlah</th>
                        <th class="border px-4 py-2">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Pastikan stokMasukResult tidak kosong sebelum looping
                    if (mysqli_num_rows($stokMasukResult) > 0) {
                        mysqli_data_seek($stokMasukResult, 0); // Reset pointer jika sudah digunakan sebelumnya
                        while ($row = mysqli_fetch_assoc($stokMasukResult)):
                    ?>
                        <tr class="text-center border-t border-gray-300">
                            <td class="px-4 py-2 whitespace-nowrap"><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['nama_barang']) ?></td>
                            <td class="px-4 py-2"><?= $row['jumlah'] ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['keterangan']) ?></td>
                        </tr>
                    <?php
                        endwhile;
                    } else {
                        // Penyesuaian Tailwind CSS untuk Pesan Tabel Kosong
                        echo '<tr class="text-center border-t border-gray-300"><td colspan="4" class="px-4 py-2">Tidak ada data stok masuk.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>