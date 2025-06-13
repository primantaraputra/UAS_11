<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/koneksi.php';

$error = '';

// Handle Tambah Supplier
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_supplier']);
    if ($nama) {
        $nama = mysqli_real_escape_string($koneksi, $nama);
        $cek = mysqli_query($koneksi, "SELECT * FROM supplier WHERE nama_supplier='$nama'");
        if (mysqli_num_rows($cek) === 0) {
            mysqli_query($koneksi, "INSERT INTO supplier (nama_supplier) VALUES ('$nama')");
            header("Location: index.php");
            exit;
        } else {
            $error = "Supplier sudah ada!";
        }
    } else {
        $error = "Nama supplier tidak boleh kosong.";
    }
}

// Handle Hapus Supplier
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $cek = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_supplier=$id");
    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($koneksi, "DELETE FROM supplier WHERE id_supplier=$id");
        header("Location: index.php");
        exit;
    } else {
        $error = "Supplier ini tidak bisa dihapus karena sedang digunakan di barang.";
    }
}

// Ambil semua supplier
$result = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY id_supplier ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Supplier - Inventory JEDI</title>
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
        .dark-mode input{
            color: black;
        }

    
    </style>
</head>
<body>
    <header class="p-4 md:p-10 mx-auto shadow-lg sticky top-0 z-50 mb-3 backdrop-blur-md w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
            <h1 class="text-xl md:text-3xl font-bold mb-3 md:mb-0">Kelola Supplier</h1> <button onclick="toggleMode()" class="bg-white/30 text-white px-4 py-2 rounded-full hover:bg-white/50 transition">
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
            <p class="text-red-200 bg-red-500/30 px-4 py-2 max-w-full md:max-w-xl rounded mb-4 text-sm mx-auto md:mx-0"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" class="bg-white p-6 rounded-2xl shadow-md max-w-full md:max-w-xl mx-auto mb-10">
            <h2 class="text-xl font-semibold mb-4 text-center">Tambah Supplier Baru</h2>
            <input type="text" name="nama_supplier" placeholder="Nama Supplier" required
                class="w-full border border-gray-300 p-2 rounded mb-4" />
            <button type="submit" name="tambah" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-2xl transition w-full">
                Tambah
            </button>
        </form>

        <h2 class="text-xl font-semibold mb-4 text-center">Daftar Supplier</h2>
        <div class="shadow rounded-2xl overflow-hidden overflow-x-auto"> <table class="min-w-full bg-white text-sm">
                <thead class="bg-gray-200 text-center">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Nama Supplier</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Pastikan $result tidak kosong sebelum looping
                    if (mysqli_num_rows($result) > 0) {
                        mysqli_data_seek($result, 0); // Reset pointer jika sudah digunakan sebelumnya
                        while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                        <tr class="text-center border-t border-gray-300">
                            <td class="px-4 py-2"><?= $row['id_supplier'] ?></td>
                            <td class="px-4 py-2"><?= $row['nama_supplier'] ?></td> <td class="px-4 py-2 space-x-2 whitespace-nowrap"> <a href="edit.php?id=<?= $row['id_supplier'] ?>" class="text-yellow-600 hover:underline">Edit</a>
                                <a href="?hapus=<?= $row['id_supplier'] ?>" onclick="return confirm('Yakin hapus supplier ini?')"
                                    class="text-red-600 hover:underline">Hapus</a>
                            </td>
                        </tr>
                    <?php 
                        endwhile; 
                    } else {
                        echo '<tr class="text-center border-t border-gray-300"><td colspan="3" class="px-4 py-2">Tidak ada data supplier.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>