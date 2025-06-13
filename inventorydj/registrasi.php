<?php
session_start();
include 'config/koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($username === '' || $password === '' || $confirm === '') {
        $error = 'Semua field wajib diisi.';
    } elseif ($password !== $confirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $username = mysqli_real_escape_string($koneksi, $username);
        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Username sudah digunakan.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($koneksi, "INSERT INTO users (username, password) VALUES ('$username', '$hash')");
            $success = 'Registrasi berhasil. Silakan login.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrasi - Inventory DJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, rgb(239, 187, 137), rgb(76, 128, 231));
            background-size: cover;
            background-attachment: fixed;
            transition: all 0.3s ease;
        }
        .dark-mode {
            background: linear-gradient(135deg, rgb(114, 61, 12), rgb(11, 36, 88));
        }

        .dark-mode input {
            color: black;
        }
    </style>
</head>
<body>
    <div class="p-5 text-right">
        <button onclick="toggleMode()" class="bg-white/30 text-white px-4 py-2 rounded-full hover:bg-white/50 transition">
            🌙 Ubah Mode
        </button>

        <script>
            function toggleMode() {
                document.body.classList.toggle("dark-mode");
            }
        </script>
    </div>

    <div class="flex items-center justify-center min-h-screen">
        <form method="POST" class="bg-white/20 backdrop-blur-md p-8 rounded-2xl shadow-xl w-full max-w-sm text-white border border-white/30 transition hover:scale-105 duration-300">
            <h2 class="text-2xl font-bold mb-6 text-center drop-shadow">Registrasi</h2>

            <?php if ($error): ?>
                <p class="text-red-500 bg-red-500/30 px-4 py-2 rounded mb-4 text-sm"><?= htmlspecialchars($error) ?></p>
            <?php elseif ($success): ?>
                <p class="text-green-600 bg-green-500/30 px-4 py-2 rounded mb-4 text-sm"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <div class="mb-4">
                <label class="block text-sm">Username</label>
                <input type="text" name="username" placeholder="Username" required class="w-full px-3 py-2 mt-1 bg-white/20 backdrop-blur-md border border-white/30 rounded placeholder-white/70 text-gray-800 focus:outline-none focus:ring-2 focus:ring-white" />
            </div>
            
            <div class="mb-4">
                <label class="block text-sm">Password</label>
                <input type="password" name="password" placeholder="Password" required class="w-full px-3 py-2 mt-1 bg-white/20 backdrop-blur-md border border-white/30 rounded placeholder-white/70 text-gray-800 focus:outline-none focus:ring-2 focus:ring-white" />
            </div>
            
            <div class="mb-4">
                <label class="block text-sm">Konfirmasi Password</label>
                <input type="password" name="confirm" placeholder="Konfirmasi Password" required class="w-full px-3 py-2 mt-1 bg-white/20 backdrop-blur-md border border-white/30 rounded placeholder-white/70 text-gray-800 focus:outline-none focus:ring-2 focus:ring-white" />
            </div>


            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-500 text-white font-semibold px-3 py-2 mt-2 rounded transition duration-300">
                Daftar
            </button>
            <p class="mt-4 text-center text-sm text-white/80">
                Sudah punya akun? 
                <a href="login.php" class="text-white underline hover:text-sky-600">Login</a>
            </p>
        </form>
    </div>

</body>
</html>