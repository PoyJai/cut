<?php
session_start();
require('server.php');

if (!isset($_SESSION['loggedin'])) { header("location: login.php"); exit; }
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT p.* FROM products p 
                       JOIN favorites f ON p.id = f.product_id 
                       WHERE f.user_id = ?");
$stmt->execute([$user_id]) ?? 1;
$favorite_items = $stmt->fetchAll();
try {
    $query = "SELECT p.* FROM products p 
              JOIN favorites f ON p.id = f.product_id 
              WHERE f.user_id = :user_id 
              ORDER BY f.created_at DESC";
              
    $stmt = $pdo->prepare($query); 
    $stmt->execute(['user_id' => $user_id]);
    $fav_products = $stmt->fetchAll();
} catch (PDOException $e) { 
    die("Error: " . $e->getMessage()); 
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .product-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .remove-anim { opacity: 0; transform: scale(0.5) translateY(20px); pointer-events: none; }
    </style>
</head>
<body class="bg-pink-50 font-['Kanit']">
    <div class="max-w-6xl mx-auto p-6">
        <div class="flex items-center justify-between mb-10">
            <a href="index.php" class="text-pink-500 font-bold flex items-center gap-2">
                <i class="fas fa-chevron-left"></i> กลับหน้าหลัก
            </a>
            <h1 class="text-3xl font-bold text-gray-800">สินค้าที่ชอบ ❤️</h1>
            <div></div>
        </div>

        <?php if (count($fav_products) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($fav_products as $item): ?>
                <div id="item-<?php echo $item['id']; ?>" class="product-card bg-white rounded-[2rem] overflow-hidden shadow-sm relative">
                    
                    <button onclick="removeItem(<?php echo $item['id']; ?>)" 
                            class="absolute top-4 right-4 w-10 h-10 bg-pink-500 text-white rounded-full flex items-center justify-center shadow-lg z-20 hover:scale-110 transition">
                        <i class="fas fa-heart"></i>
                    </button>

                    <img src="<?php echo $item['image']; ?>" class="w-full h-56 object-cover">
                    <div class="p-5">
                        <h3 class="font-bold text-gray-800"><?php echo $item['name']; ?></h3>
                        <p class="text-pink-500 font-bold">฿<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20 bg-white rounded-[2rem]">
                <p class="text-gray-400 text-xl">ยังไม่มีสินค้าที่ชอบ</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function removeItem(id) {
        const element = document.getElementById('item-' + id);
        
        // 1. ใส่ Animation ให้ค่อยๆ หายไป
        element.classList.add('remove-anim');
        
        // 2. ลบออกจากหน้าจอหลังจากเล่น Animation จบ (0.4 วินาที)
        setTimeout(() => {
            element.remove();
            
            // ถ้าลบจนหมดหน้า ให้รีโหลดเพื่อแสดงสถานะว่างเปล่า
            const remaining = document.querySelectorAll('.product-card');
            if (remaining.length === 0) {
                location.reload();
            }
        }, 400);

        // 3. (Optional) ส่งไปลบใน Database จริงๆ
        // fetch('remove_favorite.php?id=' + id);
    }
    </script>
</body>
</html>