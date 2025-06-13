<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/koneksi.php';

$error = '';

if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_kategori']);
    if ($nama) {
        $nama = mysqli_real_escape_string($koneksi, $nama);
        $cek = mysqli_query($koneksi, "SELECT * FROM kategori WHERE nama_kategori='$nama'");
        if (mysqli_num_rows($cek) === 0) {
            mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
            header("Location: index.php");
            exit;
        } else {
            $error = "Kategori sudah ada!";
        }
    } else {
        $error = "Nama kategori tidak boleh kosong.";
    }
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $cek = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_kategori=$id");
    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($koneksi, "DELETE FROM kategori WHERE id_kategori=$id");
        header("Location: index.php");
        exit;
    } else {
        $error = "Kategori ini tidak bisa dihapus karena sedang digunakan di barang.";
    }
}

$result = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kategori - Inventory DJ</title>
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
            color: black;
        }
    </style>
</head>
<body>
    <header class="p-4 md:p-10 mx-auto shadow-lg sticky top-0 z-50 mb-3 backdrop-blur-md w-full">
        <div class="flex flex-col md:flex-row justify-between items-center mb-3">
            <h1 class="text-xl md:text-3xl font-bold mb-3 md:mb-0">Kelola Kategori</h1>
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

    <div class="flex-grow p-4 md:p-10 w-full max-w-6xl mx-auto"> <a href="../index.php" class="text-white bg-white/30 px-4 py-2 rounded-2xl inline-block mb-4 hover:bg-white/50 transition">&larr; Kembali ke Dashboard</a>

        <?php if ($error): ?>
            <div class="text-red-200 bg-red-500/30 px-4 py-2 max-w-full md:max-w-xl rounded mb-4 text-sm mx-auto md:mx-0"><?= $error ?></div> <?php endif; ?>

        <form method="POST" class="bg-white p-6 rounded-2xl shadow-md max-w-full md:max-w-xl mx-auto mb-10">
            <h2 class="text-xl font-semibold mb-4 text-center">Tambah Kategori Baru</h2>
            <div class="mb-4">
                <label for="nama_kategori" class="block mb-1">Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <button type="submit" name="tambah" class="bg-blue-600 text-white px-4 py-2 rounded-2xl hover:bg-blue-700 w-full">Tambah</button>
        </form>

        <h2 class="text-xl font-semibold mb-4 text-center">Daftar Kategori</h2>
        <div class="shadow rounded-2xl overflow-hidden overflow-x-auto"> <table class="min-w-full bg-white text-sm">
                <thead class="bg-gray-200 text-center">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Nama Kategori</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($result) > 0) {
                        mysqli_data_seek($result, 0); // Reset pointer jika sudah digunakan sebelumnya
                        while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                        <tr class="text-center border-t border-gray-300">
                            <td class="px-4 py-2"><?= $row['id_kategori'] ?></td>
                            <td class="px-4 py-2"><?= $row['nama_kategori'] ?></td> <td class="px-4 py-2 space-x-2 whitespace-nowrap"> <a href="edit.php?id=<?= $row['id_kategori'] ?>" class="text-yellow-600 hover:underline">Edit</a>
                                <a href="?hapus=<?= $row['id_kategori'] ?>" onclick="return confirm('Yakin hapus kategori ini?')" class="text-red-600 hover:underline">Hapus</a>
                            </td>
                        </tr>
                    <?php 
                        endwhile; 
                    } else {
                        echo '<tr class="text-center border-t border-gray-300"><td colspan="3" class="px-4 py-2">Tidak ada data kategori.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>