<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'config/koneksi.php';

$jumlah_barang = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM barang"));
$jumlah_kategori = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kategori"));
$jumlah_supplier = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM supplier"));
$stok_masuk = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM stok_masuk"));
$stok_keluar = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM stok_keluar"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Inventory DJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, rgb(239, 187, 137), rgb(76, 128, 231));
            background-size: cover;
            background-attachment: fixed;
            transition: all 1s ease;
        }

        .dark-mode {
            background: linear-gradient(135deg, rgb(114, 61, 12), rgb(11, 36, 88));
            color: white;
        }

        .dark-mode .bg-white {
            background-color: #1f2937;
            color: white;
        }

        .dark-mode .bg-gray-200 {
            background-color: #374151;
        }

        .dark-mode .border-gray-300 {
            border-color: #4b5563;
        }

        .dark-mode th,
        .dark-mode td {
            color: white;
        }

        .dark-mode .footer-gradient {
            background: linear-gradient(135deg, rgba(17, 24, 39, 0.9), rgba(31, 41, 55, 0.9));
        }

        .footer-gradient {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        }

        /* Custom styling for sleek links */
        .sleek-link {
            position: relative;
            display: inline-block;
            padding-bottom: 2px; /* Space for underline */
        }
        .sleek-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 1px;
            background-color: white;
            transition: width 0.3s ease-in-out;
        }
        .sleek-link:hover::after {
            width: 100%;
        }

    </style>
</head>
<body>
    <header class="p-4 md:p-10 mx-auto shadow-lg sticky top-0 z-50 mb-3 backdrop-blur-md w-full">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h1 class="text-xl md:text-3xl font-bold mb-3 md:mb-0">Dashboard Inventory DJ</h1>
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
        
    <div class="p-4 md:p-10 min-h-screen"> 
        <div class="flex flex-col items-center justify-center w-full max-w-6xl mx-auto">
            <nav class="mb-6 flex flex-wrap justify-center gap-2 md:gap-4 text-sm md:text-base">
                <a href="barang/" class="text-white bg-white/35 px-3 py-1 md:px-4 md:py-2 rounded-2xl hover:bg-blue-400 transition text-center">Barang</a>
                <a href="kategori/" class="text-white bg-white/35 px-3 py-1 md:px-4 md:py-2 rounded-2xl hover:bg-blue-400 transition text-center">Kategori</a>
                <a href="supplier/" class="text-white bg-white/35 px-3 py-1 md:px-4 md:py-2 rounded-2xl hover:bg-blue-400 transition text-center">Supplier</a>
                <a href="stok_masuk/" class="text-white bg-white/35 px-3 py-1 md:px-4 md:py-2 rounded-2xl hover:bg-green-400 transition text-center">Stok Masuk</a>
                <a href="stok_keluar/" class="text-white bg-white/35 px-3 py-1 md:px-4 md:py-2 rounded-2xl hover:bg-red-400 transition text-center">Stok Keluar</a>
                <a href="logout.php" class="text-white bg-red-500/35 px-3 py-1 md:px-4 md:py-2 rounded-2xl hover:bg-red-400 transition text-center">Logout</a>
            </nav>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-5 w-full">
                <div class="bg-white p-4 rounded-2xl shadow-md text-center hover:scale-105 transition">
                    <h2 class="text-lg font-semibold">Jumlah Barang</h2>
                    <p class="text-3xl mt-2 text-blue-600"><?= $jumlah_barang ?></p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-md text-center hover:scale-105 transition">
                    <h2 class="text-lg font-semibold">Jumlah Kategori</h2>
                    <p class="text-3xl mt-2 text-blue-600"><?= $jumlah_kategori ?></p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-md text-center hover:scale-105 transition">
                    <h2 class="text-lg font-semibold">Jumlah Supplier</h2>
                    <p class="text-3xl mt-2 text-blue-600"><?= $jumlah_supplier ?></p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10 w-full">
                <div class="bg-white p-4 rounded-2xl shadow-md text-center hover:scale-105 transition">
                    <h2 class="text-lg font-semibold">Riwayat Stok Masuk</h2>
                    <p class="text-3xl mt-2 text-green-600"><?= $stok_masuk ?></p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-md text-center hover:scale-105 transition">
                    <h2 class="text-lg font-semibold">Riwayat Stok Keluar</h2>
                    <p class="text-3xl mt-2 text-red-600"><?= $stok_keluar ?></p>
                </div>
            </div>
        </div>

        <h2 class="text-xl text-center font-semibold mb-4">Ringkasan Stok Barang</h2>
        <div class="shadow rounded-2xl overflow-x-auto mb-20"> 
            <table class="min-w-full text-sm bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class=" px-4 py-2">No</th>
                        <th class=" px-4 py-2">Nama Barang</th>
                        <th class=" px-4 py-2">Kategori</th>
                        <th class=" px-4 py-2">Supplier</th>
                        <th class=" px-4 py-2">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($koneksi, "
                        SELECT b.nama_barang, k.nama_kategori, s.nama_supplier, b.stok
                        FROM barang b
                        LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
                        LEFT JOIN supplier s ON b.id_supplier = s.id_supplier
                    ");
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr class="text-center border-t border-gray-300 hover:bg-gray-400/50 transition">';
                        echo '<td class="px-4 py-2 text-center">' . $no++ . '</td>';
                        echo '<td class="px-4 py-2 text-center">' . $row['nama_barang'] . '</td>';
                        echo '<td class="px-4 py-2 text-center">' . $row['nama_kategori'] . '</td>';
                        echo '<td class="px-4 py-2 text-center">' . $row['nama_supplier'] . '</td>';
                        echo '<td class="px-4 py-2">' . $row['stok'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="footer-gradient backdrop-blur-lg border-t border-white/20 mt-20">
        <div class="max-w-6xl mx-auto px-6 py-12 text-white">
            <div class="flex flex-col lg:flex-row justify-between items-center lg:items-start gap-10">
                <div class="text-center lg:text-left lg:w-1/3">
                    <h2 class="text-3xl font-bold mb-3">Inventory DJ</h2>
                    <p class="text-white/70 text-base leading-relaxed">
                        Manajemen inventori modern yang efisien.
                        Optimalkan operasional bisnis Anda dengan solusi terkini dari kami.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row justify-around flex-grow gap-8 w-full lg:w-2/3">
                    <div class="text-center sm:text-left flex-1">
                        <h3 class="text-xl font-semibold mb-4">Navigasi</h3>
                        <ul class="space-y-3">
                            <li><a href="barang/" class="text-white/80 sleek-link">Manajemen Barang</a></li>
                            <li><a href="kategori/" class="text-white/80 sleek-link">Kategori Produk</a></li>
                            <li><a href="supplier/" class="text-white/80 sleek-link">Daftar Supplier</a></li>
                        </ul>
                    </div>

                    <div class="text-center sm:text-left flex-1">
                        <h3 class="text-xl font-semibold mb-4">Transaksi</h3>
                        <ul class="space-y-3">
                            <li><a href="stok_masuk/" class="text-white/80 sleek-link">Riwayat Stok Masuk</a></li>
                            <li><a href="stok_keluar/" class="text-white/80 sleek-link">Riwayat Stok Keluar</a></li>
                            <li><a href="#" class="text-white/80 sleek-link">Laporan Inventori</a></li>
                        </ul>
                    </div>
                </div>

                <div class="text-center lg:text-right lg:w-1/3 mt-6 lg:mt-0">
                    <h3 class="text-xl font-semibold mb-4">Kontak & Dukungan</h3>
                    <address class="not-italic text-white/70 space-y-2 text-base">
                        <p>Bangkalan, Madura, Indonesia</p>
                        <p>Email: <a href="mailto:info@inventorydj.com" class="sleek-link">info@inventorydj.com</a></p>
                        <p>Telepon: <a href="tel:+6287717511477" class="sleek-link">+62 877-175-1477</a></p>
                    </address>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-white/20 text-center text-sm flex flex-col md:flex-row justify-between items-center">
                <p class="text-white/60 mb-3 md:mb-0">
                    &copy; <?= date('Y') ?> Inventory DJ. Semua Hak Dilindungi.
                </p>
                <div class="flex items-center space-x-2 bg-white/10 px-4 py-1.5 rounded-full border border-white/10">
                    <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-white/80 font-medium">Sistem Aktif</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>