<?php
session_start();
require_once('server.php');
if (!isset($_SESSION['loggedin'])) { header("location: login.php"); exit; }

$stmt = $pdo->prepare("SELECT p.* FROM products p JOIN favorites f ON p.id = f.product_id WHERE f.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$favs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>WISHLIST | THE CORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syncopate:wght@700&family=Kanit:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #050505; color: white; font-family: 'Kanit', sans-serif; }
        .font-sync { font-family: 'Syncopate', sans-serif; }
    </style>
</head>
<body class="flex">
    <nav class="fixed left-0 top-0 h-full w-20 flex flex-col items-center py-8 bg-[#0a0a0a] border-r border-white/5">
        <a href="index.php" class="text-pink-600 text-3xl font-sync font-bold italic mb-12">S</a>
        <div class="flex flex-col gap-10">
            <a href="index.php" class="text-gray-600 hover:text-white transition text-xl"><i class="fas fa-ghost"></i></a>
            <a href="category.php" class="text-gray-600 hover:text-white transition text-xl"><i class="fas fa-box-open"></i></a>
            <a href="Favorite.php" class="text-pink-500 text-xl"><i class="fas fa-heart"></i></a>
        </div>
    </nav>

    <main class="ml-20 p-12 w-full">
        <header class="mb-20">
            <h2 class="font-sync text-5xl italic tracking-tighter">SAVED<br><span class="text-pink-600">ITEMS.</span></h2>
            <div class="h-1 w-24 bg-pink-600 mt-4"></div>
        </header>

        <?php if (empty($favs)): ?>
            <div class="text-center py-40 border border-white/5 bg-[#0d0d0d]">
                <p class="text-gray-600 font-sync text-xl tracking-widest uppercase">Your wishlist is empty</p>
                <a href="index.php" class="inline-block mt-8 text-pink-500 hover:underline">RETURN TO SHOP</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <?php foreach ($favs as $p): ?>
                <div class="relative bg-[#0d0d0d] group">
                    <img src="<?php echo $p['image']; ?>" class="w-full aspect-[3/4] object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 uppercase tracking-tighter"><?php echo $p['name']; ?></h3>
                        <p class="text-pink-600 font-bold mb-4">฿<?php echo number_format($p['price'], 2); ?></p>
                        <button onclick="removeFav(<?php echo $p['id']; ?>, this)" class="w-full py-3 bg-white/5 hover:bg-red-600 transition font-bold text-xs tracking-widest uppercase">Remove</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function removeFav(id, btn) {
            const formData = new FormData();
            formData.append('product_id', id);
            fetch('save_favorite.php', { method: 'POST', body: formData })
            .then(() => btn.closest('.relative').remove());
        }
    </script>
</body>
</html>