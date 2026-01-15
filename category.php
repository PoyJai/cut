<?php
session_start();
require('server.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

// รับค่าหมวดหมู่จาก URL (ถ้าไม่มีให้ว่างไว้เพื่อแสดงทั้งหมด)
$category_filter = isset($_GET['type']) ? $_GET['type'] : '';

// --- ระบบแบ่งหน้า ---
$items_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $items_per_page;

try {
    // 1. ดึงรายชื่อหมวดหมู่ทั้งหมดที่มีในระบบมาแสดงที่ Sidebar
    $cat_list_stmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''");
    $all_categories = $cat_list_stmt->fetchAll(PDO::FETCH_COLUMN);

    // 2. นับจำนวนสินค้า (แยกตามหมวดหมู่ถ้ามีการเลือก)
    if ($category_filter) {
        $total_stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category = ?");
        $total_stmt->execute([$category_filter]);
    } else {
        $total_stmt = $pdo->query("SELECT COUNT(*) FROM products");
    }
    $total_items = $total_stmt->fetchColumn();
    $total_pages = ceil($total_items / $items_per_page);

    // 3. ดึงข้อมูลสินค้า
    $sql = "SELECT * FROM products";
    if ($category_filter) {
        $sql .= " WHERE category = :category";
    }
    $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    if ($category_filter) {
        $stmt->bindValue(':category', $category_filter, PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หมวดหมู่สินค้า | SUNSHINE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #fffafb; }
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px -5px rgba(255, 182, 193, 0.3); }
        #sidebar { transition: all 0.3s ease-in-out; }
        @media (max-width: 1024px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
        }
        .active-category { background-color: #fce7f3; color: #ec4899; font-weight: 600; border-left: 4px solid #ec4899; }
    </style>
</head>
<body>

    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/20 z-[60] hidden backdrop-blur-sm lg:hidden"></div>

    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 z-[70] bg-white border-r border-pink-50 flex flex-col shadow-xl lg:shadow-none">
        <div class="p-6 flex items-center gap-3 border-b border-pink-50">
            <div class="bg-pink-400 p-2 rounded-xl text-white"><i class="fas fa-tshirt"></i></div>
            <span class="text-xl font-bold text-pink-500 tracking-wider">SUNSHINE</span>
        </div>

        <div class="flex-grow py-6 px-4 space-y-1 overflow-y-auto">
            <p class="text-[10px] font-bold text-pink-300 uppercase tracking-widest px-4 mb-2">เมนู</p>
            <a href="index.php" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:bg-pink-50 hover:text-pink-500 rounded-2xl transition">
                <i class="fas fa-home w-5"></i> หน้าแรก
            </a>
            
            <p class="text-[10px] font-bold text-pink-300 uppercase tracking-widest px-4 mt-6 mb-2">หมวดหมู่สินค้า</p>
            <a href="category.php" class="flex items-center gap-4 px-4 py-3 rounded-2xl transition <?php echo $category_filter == '' ? 'active-category' : 'text-gray-400 hover:bg-pink-50'; ?>">
                <i class="fas fa-border-all w-5"></i> ทั้งหมด
            </a>
            <?php foreach ($all_categories as $cat): ?>
            <a href="category.php?type=<?php echo urlencode($cat); ?>" 
               class="flex items-center gap-4 px-4 py-3 rounded-2xl transition <?php echo $category_filter == $cat ? 'active-category' : 'text-gray-400 hover:bg-pink-50 hover:text-pink-500'; ?>">
                <i class="fas fa-tag w-5"></i> <?php echo htmlspecialchars($cat); ?>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="p-6 border-t border-pink-50">
            <a href="login.php" class="flex items-center justify-center gap-2 w-full py-3 text-gray-400 hover:text-red-400 text-sm transition">
                <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
            </a>
        </div>
    </aside>

    <div class="lg:ml-64 min-h-screen">
        <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 lg:hidden border-b border-pink-50 px-6 py-4 flex justify-between items-center">
            <button onclick="toggleSidebar()" class="text-gray-500 text-xl"><i class="fas fa-bars"></i></button>
            <div class="text-pink-500 font-bold uppercase">SUNSHINE</div>
            <div class="w-10"></div> 
        </nav>

        <main class="max-w-6xl mx-auto p-6 md:p-10">
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-gray-800">
                    <?php echo $category_filter ? 'หมวดหมู่: ' . htmlspecialchars($category_filter) : 'สินค้าทั้งหมด'; ?>
                </h1>
                <p class="text-gray-400 mt-2">พบสินค้าทั้งหมด <?php echo $total_items; ?> รายการ</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $item): ?>
                    <div class="product-card bg-white rounded-[2rem] overflow-hidden border border-pink-50 flex flex-col shadow-sm">
                        <div class="relative h-60">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <span class="text-[10px] text-pink-400 font-bold uppercase mb-1"><?php echo htmlspecialchars($item['category'] ?? 'General'); ?></span>
                            <h3 class="text-gray-800 font-semibold mb-2 line-clamp-1"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="text-pink-500 font-bold text-lg mb-4">฿<?php echo number_format($item['price'], 2); ?></p>
                            <button class="mt-auto w-full py-2.5 bg-pink-50 text-pink-500 rounded-xl font-bold hover:bg-pink-500 hover:text-white transition">
                                ดูรายละเอียด
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-20 text-center">
                        <i class="fas fa-search text-5xl text-pink-100 mb-4"></i>
                        <p class="text-gray-400 text-lg">ไม่พบสินค้าในหมวดหมู่นี้</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
            <div class="flex justify-center mt-12 gap-2">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?type=<?php echo urlencode($category_filter); ?>&page=<?php echo $i; ?>" 
                       class="w-10 h-10 flex items-center justify-center rounded-xl font-bold transition <?php echo ($i == $current_page) ? 'bg-pink-500 text-white shadow-lg' : 'bg-white text-gray-400 border border-pink-100 hover:bg-pink-50'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('hidden');
        }
    </script>
</body>
</html>