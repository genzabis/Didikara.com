<?php
session_start();
// Jika user sudah login, langsung alihkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php?view=dashboard');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Didikara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-sm bg-white rounded-lg shadow-md p-8">
        <div class="flex justify-center mb-6">
            <img src="../assets/img/didikara.png" class="w-40 h-auto" alt="logo">
        </div>
        <h2 class="text-xl font-bold text-center text-gray-700 mb-1">Admin Panel</h2>
        <p class="text-center text-gray-500 text-sm mb-6">Silakan masuk untuk melanjutkan</p>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                <span><?= htmlspecialchars($_GET['error']) ?></span>
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Alamat Email
                </label>
                <input class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" id="email" name="email" type="email" placeholder="admin@example.com" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" id="password" name="password" type="password" placeholder="******************" required>
            </div>
            <div class="flex items-center justify-center">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full focus:outline-none focus:shadow-outline" type="submit">
                    Masuk
                </button>
            </div>
        </form>
    </div>

</body>
</html>