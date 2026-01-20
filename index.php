<?php
session_start();
require_once('server.php'); 

// 1. ตรวจสอบการ Login
if (!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit;
}

// 2. ตั้งค่าตัวแปรพื้นฐานป้องกัน Error
$category_filter = isset($_GET['type']) ? $_GET['type'] : '';
$items_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $items_per_page;

try {
    // 3. ดึงรายการหมวดหมู่ (สำหรับปุ่มกรอง)
    $cat_stmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''");
    $all_categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

    // 4. ดึงข้อมูลสินค้า (ใช้รูป image2, image3 จาก SQL ตัวใหม่ด้วย)
    $sql = "SELECT p.*, f.id AS is_favorite 
            FROM products p 
            LEFT JOIN favorites f ON p.id = f.product_id AND f.user_id = :user_id";
    if ($category_filter) {
        $sql .= " WHERE p.category = :cat";
    }
    $sql .= " ORDER BY p.id DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    if ($category_filter) {
        $stmt->bindValue(':cat', $category_filter);
    }
    $stmt->execute();
    $products = $stmt->fetchAll();

    // 5. คำนวณจำนวนหน้าทั้งหมด
    $count_sql = "SELECT COUNT(*) FROM products";
    if ($category_filter) $count_sql .= " WHERE category = :cat";
    $total_stmt = $pdo->prepare($count_sql);
    if ($category_filter) $total_stmt->execute(['cat' => $category_filter]);
    else $total_stmt->execute();
    $total_pages = ceil($total_stmt->fetchColumn() / $items_per_page);

} catch (PDOException $e) {
    die("เชื่อมต่อฐานข้อมูลไม่ได้: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>TOY LAND | อาณาจักรของเล่น</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Itim&family=Kanit:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f0f9ff; font-family: 'Kanit', sans-serif; }
        .font-itim { font-family: 'Itim', cursive; }
        .toy-card { background: white; border-radius: 2rem; border-bottom: 8px solid #e2e8f0; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .toy-card:hover { transform: translateY(-10px); border-bottom-color: #fbbf24; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .cat-tag.active { background-color: #fbbf24; color: white; transform: scale(1.1); box-shadow: 0 4px 14px rgba(251, 191, 36, 0.4); }
        .modal-active { display: flex !important; }
        .thumb-active { border-color: #fbbf24 !important; opacity: 1 !important; }
        .float-toy { animation: floating 3s ease-in-out infinite; }
        @keyframes floating { 0%, 100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-10px) rotate(2deg); } }
    </style>
</head>
<body>

    <nav class="bg-white border-b-4 border-yellow-400 sticky top-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="index.php" class="font-itim text-4xl text-yellow-500 flex items-center gap-2">
                <i class="fas fa-rocket float-toy text-blue-400"></i> TOY LAND
            </a>
            <div class="flex items-center gap-5">
                <a href="checkout.php" class="relative text-blue-500 hover:scale-110 transition">
                    <i class="fas fa-shopping-basket text-2xl"></i>
                    <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full font-bold">
                        <?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
                    </span>
                </a>
                <a href="Favorite.php" class="text-pink-400 hover:scale-125 transition"><i class="fas fa-heart text-2xl"></i></a>
                <div class="bg-blue-50 px-4 py-2 rounded-full border-2 border-blue-100 flex items-center gap-2">
                    <span class="text-sm font-bold text-blue-600"><?php echo htmlspecialchars($_SESSION['username']); ?> 👩‍🚀</span>
                    <a href="login.php?logout=1" class="text-red-400 hover:text-red-600"><i class="fas fa-power-off"></i></a>
                </div>
            </div>           
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-8">
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <a href="index.php" class="cat-tag px-8 py-3 rounded-full bg-white text-gray-500 font-bold shadow-sm <?php echo !$category_filter ? 'active' : ''; ?>">ทั้งหมด 🧸</a>
            <?php foreach ($all_categories as $cat): ?>
                <a href="?type=<?php echo urlencode($cat); ?>" class="cat-tag px-8 py-3 rounded-full bg-white text-gray-500 font-bold shadow-sm <?php echo ($category_filter == $cat) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($cat); ?> 🎲
                </a>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <?php foreach ($products as $p): ?>
            <div class="toy-card p-5 flex flex-col relative overflow-hidden">
                <img src="<?php echo htmlspecialchars($p['image']); ?>" class="w-full h-56 object-contain rounded-2xl mb-4 transition-transform hover:scale-110 duration-500">
                <div class="flex-grow">
                    <span class="text-blue-400 text-xs font-bold uppercase"><?php echo htmlspecialchars($p['category']); ?></span>
                    <h3 class="font-bold text-xl text-gray-700 mt-1 truncate"><?php echo htmlspecialchars($p['name']); ?></h3>
                    <div class="flex justify-between items-end mt-5">
                        <p class="text-3xl font-bold text-yellow-500">฿<?php echo number_format($p['price']); ?></p>
                        <button onclick='openProductModal(<?php echo json_encode($p); ?>)' class="bg-blue-500 text-white w-12 h-12 rounded-2xl hover:bg-yellow-400 transition shadow-lg flex items-center justify-center">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="flex justify-center mt-16 gap-3">
            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <a href="?type=<?php echo urlencode($category_filter); ?>&page=<?php echo $i; ?>" 
                   class="w-12 h-12 flex items-center justify-center rounded-2xl font-bold transition-all <?php echo ($i==$current_page) ? 'bg-yellow-400 text-white shadow-lg' : 'bg-white text-gray-400 hover:bg-yellow-50'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </main>

    <div id="productModal" class="fixed inset-0 bg-blue-900/40 backdrop-blur-sm hidden z-[100] items-center justify-center p-4">
        <div class="bg-white w-full max-w-5xl rounded-[3rem] shadow-2xl flex flex-col md:flex-row relative overflow-hidden border-8 border-white">
            <button onclick="closeModal()" class="absolute top-6 right-6 z-10 w-12 h-12 bg-gray-100 text-gray-400 rounded-full hover:bg-red-400 hover:text-white transition"><i class="fas fa-times"></i></button>
            <div class="w-full md:w-1/2 p-8 bg-yellow-50/50 flex flex-col">
                <div class="bg-white rounded-3xl p-4 shadow-inner flex-grow flex items-center justify-center">
                    <img id="modalImg" src="" class="max-w-full max-h-80 object-contain transition-all duration-300">
                </div>
                <div id="modalGallery" class="flex gap-3 mt-6 justify-center"></div>
            </div>
            <div class="w-full md:w-1/2 p-12 flex flex-col">
                <span id="modalCat" class="bg-blue-100 text-blue-600 px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest"></span>
                <h2 id="modalTitle" class="font-itim text-5xl text-gray-700 mt-4 mb-2"></h2>
                <p id="modalPrice" class="text-4xl font-bold text-yellow-500 mb-6"></p>
                <div class="bg-blue-50 p-6 rounded-3xl mb-8">
                    <h4 class="font-bold text-blue-600 mb-2">🚀 ข้อมูลสินค้า:</h4>
                    <p id="modalDesc" class="text-gray-600 text-sm leading-relaxed"></p>
                </div>
                <div class="mt-auto flex gap-4">
                    <button onclick="addToCart(currentProductId)" class="flex-grow bg-blue-500 text-white py-5 rounded-3xl font-bold text-xl hover:bg-blue-600 shadow-xl transition active:scale-95">หยิบลงตะกร้า 🧺</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentProductId = null;
        
        function openProductModal(p) {
            currentProductId = p.id;
            document.getElementById('modalTitle').innerText = p.name;
            document.getElementById('modalPrice').innerText = '฿' + Number(p.price).toLocaleString();
            document.getElementById('modalCat').innerText = p.category;
            document.getElementById('modalDesc').innerText = p.description || 'ผลิตจากวัสดุปลอดภัย 100% เสริมสร้างจินตนาการ เหมาะสำหรับเด็กๆ';
            const mainImg = document.getElementById('modalImg');
            mainImg.src = p.image;

            const gallery = document.getElementById('modalGallery');
            gallery.innerHTML = '';
            
            // กรองรูปที่มีข้อมูล (image, image2, image3)
            const imgs = [p.image, p.image2, p.image3].filter(src => src !== null && src !== '');
            imgs.forEach((src, i) => {
                const thumb = document.createElement('div');
                thumb.className = `w-16 h-16 rounded-xl border-4 cursor-pointer overflow-hidden transition-all opacity-60 hover:opacity-100 ${i===0?'thumb-active':''}`;
                thumb.innerHTML = `<img src="${src}" class="w-full h-full object-contain bg-white">`;
                thumb.onclick = () => {
                    mainImg.style.opacity = '0';
                    setTimeout(() => { mainImg.src = src; mainImg.style.opacity = '1'; }, 150);
                    document.querySelectorAll('#modalGallery div').forEach(d => d.classList.remove('thumb-active'));
                    thumb.classList.add('thumb-active');
                };
                gallery.appendChild(thumb);
            });
            document.getElementById('productModal').classList.add('modal-active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('productModal').classList.remove('modal-active');
            document.body.style.overflow = 'auto';
        }

        function addToCart(productId) {
            const formData = new FormData();
            formData.append('product_id', productId);
            fetch('cart_action.php', { method: 'POST', body: formData })
            .then(res => res.json()).then(data => {
                if(data.success) {
                    document.getElementById('cart-count').innerText = data.total_items;
                    alert('เพิ่มของเล่นลงตะกร้าแล้ว! 🤖');
                }
            });
        }
    </script>
</body>
</html>