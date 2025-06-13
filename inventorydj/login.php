<?php
session_start();
include 'config/koneksi.php';

if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = ($_POST['username'] );
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau Password salah!";
        }
    } else {
        $error = "Semua field wajib diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Inventory DJ</title>
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
        <form method="POST" class="bg-white/20 backdrop-blur-md p-8 rounded-2xl shadow-xl w-full max-w-sm text-white border border-white/30 transition hover:scale-105">
            <h2 class="text-2xl font-bold mb-6 text-center drop-shadow">Login Inventory DJ</h2>
            
            <?php if ($error): ?>
                <p class="text-red-200 bg-red-500/30 px-4 py-2 rounded mb-4 text-sm"><?php echo $error; ?></p>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Username</label>
                <input type="text" name="username" placeholder="Username" class="w-full px-3 py-2 mt-1 bg-white/20 backdrop-blur-md border border-white/30 rounded placeholder-white/70 text-gray-800 focus:outline-none focus:ring-2 focus:ring-white" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Password</label>
                <input type="password" name="password" placeholder="*******" class="w-full px-3 py-2 mt-1 bg-white/20 backdrop-blur-md border border-white/30 rounded placeholder-white/70 text-gray-800 focus:outline-none focus:ring-2 focus:ring-white" required>
            </div>

            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-500 text-white font-semibold px-3 py-2 mt-2 rounded transition duration-300">
                Login
            </button>

            <p class="mt-4 text-center text-sm text-white/80">
                Belum punya akun?
                <a href="registrasi.php" class="text-white underline hover:text-sky-600">Daftar di sini</a>
            </p>
        </form>
    </div>
    
</body>
</html>