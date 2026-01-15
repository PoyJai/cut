<?php
session_start();
include('server.php'); 


if (isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header("location: login.php");
        exit;
    }
    try {
$stmt = $pdo->prepare("
    SELECT p.*, f.id AS is_favorite 
    FROM products p 
    LEFT JOIN favorites f ON p.id = f.product_id AND f.user_id = :user_id 
    ORDER BY p.id DESC LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                
                // --- จุดสำคัญที่เพิ่มเข้ามา ---
                $_SESSION['user_id'] = $user['id']; 
                // --------------------------

                header("location: index.php");
                exit;
            } else {
                $_SESSION['error'] = "รหัสผ่านไม่ถูกต้อง";
            }
        } else {
            $_SESSION['error'] = "ไม่พบชื่อผู้ใช้งาน";
        }
        header("location: login.php");
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าแรก | SUNSHINECute Fashion Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #fffafb; }
        .product-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 20px 25px -5px rgba(255, 182, 193, 0.4); }
        .sidebar-gradient { background: white; border-right: 1px solid #ffe4e6; }
        .modal-active { display: flex !important; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        
        #sidebar { transition: all 0.3s ease-in-out; }
        @media (max-width: 1024px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
        }
        .pagination-link { transition: all 0.2s; }
        .pagination-link:hover { transform: scale(1.1); }

        .wishlist-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 50 !important; 
            transition: all 0.3s ease;
        }

        .heart-active {
            color: #ec4899 !important;
            background-color: white !important;
        }

        .heart-active i {
            animation: heartPop 0.3s ease;
        }

        @keyframes heartPop {
            0% { transform: scale(1); }
            50% { transform: scale(1.4); }
            100% { transform: scale(1.1); }
        }
        .heart-active i {
            color: #ec4899 !important; /* สี pink-500 */
            transform: scale(1.2);
        }
        .heart-active {
            background-color: #fdf2f8 !important; /* pink-50 */
        }
        .heart-active {
        background-color: #fdf2f8 !important; /* พื้นหลังสีชมพูอ่อน */
        border: 1px solid #f9a8d4 !important;
        }
        
    </style>
</head>
<body class="bg-[#fffafb]">

    <!-- Overlay สำหรับ Mobile Sidebar -->
    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/20 z-[60] hidden backdrop-blur-sm lg:hidden"></div>

    <!-- 1. Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 z-[70] sidebar-gradient shadow-2xl lg:shadow-none flex flex-col">
        <div class="p-6 flex items-center gap-3 border-b border-pink-50">
            <div class="bg-pink-400 p-2 rounded-xl text-white shadow-lg">
                <i class="fas fa-tshirt"></i>
            </div>
            <span class="text-xl font-bold text-pink-500 tracking-wider">SUNSHINE</span>
        </div>

        <div class="flex-grow py-6 px-4 space-y-2 overflow-y-auto">
            <p class="text-[10px] font-bold text-pink-300 uppercase tracking-[0.2em] px-4 mb-4">เมนูหลัก</p>
            <a href="index.php" class="flex items-center gap-4 px-4 py-3 bg-pink-50 text-pink-600 rounded-2xl font-semibold transition shadow-sm">
                <i class="fas fa-home w-5"></i> หน้าแรก
            </a>
            <a href="category.php" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:bg-pink-50 hover:text-pink-500 rounded-2xl transition">
                <i class="fas fa-th-large w-5"></i> หมวดหมู่
            </a>
            <a href="Favorite.php" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:bg-pink-50 hover:text-pink-500 rounded-2xl transition">
                <i class="fas fa-heart w-5"></i> สินค้าที่ชอบ
            </a>
        </div>

        <div class="p-6 border-t border-pink-50">
            <div class="bg-gray-50 rounded-[1.5rem] p-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-pink-200 rounded-full flex items-center justify-center text-pink-500 font-bold border-2 border-white shadow-sm uppercase">
                    <?php echo substr($_SESSION['username'], 0, 1); ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-gray-800 truncate"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    <p class="text-[10px] text-gray-400">สมาชิกคนสำคัญ</p>
                </div>
            </div>
            <a href="login.php" class="mt-4 flex items-center justify-center gap-2 w-full py-3 text-gray-400 hover:text-red-400 text-sm font-medium transition">
                <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="lg:ml-64 transition-all duration-300">
        
        <!-- Mobile Header -->
        <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 lg:hidden border-b border-pink-50 px-6 py-4 flex justify-between items-center">
            <button onclick="toggleSidebar()" class="text-gray-500 text-xl p-2"><i class="fas fa-bars"></i></button>
            <div class="text-pink-500 font-bold tracking-widest text-lg uppercase">SUNSHINE</div>
            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center text-pink-500"><i class="fas fa-shopping-cart"></i></div>
        </nav>

        <!-- Banner -->
        <header class="max-w-6xl mx-auto mt-8 px-4">
            <div class="bg-gradient-to-br from-pink-100 to-pink-50 rounded-[2.5rem] p-8 md:p-12 flex flex-col md:flex-row items-center justify-between relative overflow-hidden shadow-sm border border-white">
                <div class="relative z-10 text-center md:text-left">
                    <h1 class="text-3xl md:text-5xl font-bold text-pink-600 mb-4">คอลเลกชันใหม่ล่าสุด ✨</h1>
                    <p class="text-gray-600 mb-6 text-lg">แสดงสินค้า 8 ชิ้นต่อหน้า เพื่อการเลือกที่ง่ายขึ้น</p>
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        <span class="bg-white/60 px-4 py-1.5 rounded-full text-pink-500 text-xs font-bold border border-pink-100">หน้าที่ <?php echo $current_page; ?> / <?php echo $total_pages; ?></span>
                    </div>
                </div>
                <div class="mt-8 md:mt-0 relative z-10">
                    <svg width="150" height="150" viewBox="0 0 100 100" class="drop-shadow-2xl">
                        <circle cx="50" cy="50" r="45" fill="#ffccd5" />
                        <circle cx="30" cy="35" r="8" fill="#a67c52" />
                        <circle cx="70" cy="35" r="8" fill="#a67c52" />
                        <circle cx="50" cy="60" r="30" fill="#c49a6c" />
                        <circle cx="42" cy="55" r="2.5" fill="#333" />
                        <circle cx="58" cy="55" r="2.5" fill="#333" />
                        <ellipse cx="50" cy="65" rx="10" ry="8" fill="#e8d3bc" />
                        <circle cx="50" cy="63" r="2" fill="#333" />
                    </svg>
                </div>
            </div>
        </header>

        <!-- Product Grid (8 Items per Page) -->
        <main class="max-w-6xl mx-auto mt-12 px-4 mb-10">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-pink-400 pl-4">สินค้ามาใหม่ (หน้า <?php echo $current_page; ?>)</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-8">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $item): ?>
                        <div class="product-card bg-white rounded-[2rem] overflow-hidden shadow-sm border border-pink-50 flex flex-col group relative">
                            
                            <button onclick="toggleWishlist(this, <?php echo $product['id']; ?>)" 
                                class="absolute top-4 right-4 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-pink-500 transition-all duration-300 z-10 shadow-sm 
                                <?php echo in_array($product['id'], $user_favs) ? 'heart-active' : ''; ?>">
                                <i class="fas fa-heart"></i>
                            </button>

                            <div class="relative h-64 overflow-hidden cursor-pointer" onclick='openProductModal(<?php echo json_encode($item); ?>)'>
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                
                                <?php if (!empty($item['tag'])): ?>
                                <span class="absolute top-4 left-4 bg-pink-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                                    <?php echo htmlspecialchars($item['tag']); ?>
                                </span>
                                <?php endif; ?>
                            </div>

                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="text-gray-800 font-semibold text-lg mb-1 line-clamp-1 cursor-pointer" onclick='openProductModal(<?php echo json_encode($item); ?>)'>
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </h3>
                                <p class="text-pink-500 font-bold text-xl mb-4">฿<?php echo number_format($item['price'], 2); ?></p>
                                
                                <button onclick='openProductModal(<?php echo json_encode($item); ?>)' 
                                        class="mt-auto w-full py-3 bg-pink-50 text-pink-500 rounded-2xl font-bold hover:bg-pink-500 hover:text-white transition-all shadow-sm">
                                    ดูรายละเอียด
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-20 text-center">
                        <i class="fas fa-box-open text-5xl text-pink-200 mb-4"></i>
                        <p class="text-gray-400">ไม่มีสินค้าในหน้านี้</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- --- ระบบ Pagination UI --- -->
            <?php if ($total_pages > 1): ?>
            <div class="flex justify-center items-center mt-16 gap-3">
                <!-- ปุ่มก่อนหน้า -->
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?>" class="w-12 h-12 flex items-center justify-center bg-white border border-pink-100 rounded-2xl text-pink-500 shadow-sm hover:bg-pink-500 hover:text-white transition-all">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <!-- ตัวเลขหน้า -->
                <div class="flex gap-2">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="w-12 h-12 flex items-center justify-center rounded-2xl font-bold shadow-sm pagination-link <?php echo ($i == $current_page) ? 'bg-pink-500 text-white' : 'bg-white text-gray-400 border border-pink-100 hover:text-pink-500'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>

                <!-- ปุ่มถัดไป -->
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?>" class="w-12 h-12 flex items-center justify-center bg-white border border-pink-100 rounded-2xl text-pink-500 shadow-sm hover:bg-pink-500 hover:text-white transition-all">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </main>

        <footer class="py-10 text-center text-gray-300 text-xs italic">
            © 2024 SUNSHINE Fashion Shop. Happy Shopping!
        </footer>
    </div>
    <div id="productModal" class="fixed inset-0 bg-black/90 hidden z-[100] items-center justify-center p-0 md:p-6 backdrop-blur-xl">
     <div class="bg-white md:rounded-[3rem] w-full max-w-7xl h-full md:h-[90vh] overflow-hidden relative shadow-2xl flex flex-col md:flex-row">
        
        <button onclick="closeModal()" class="absolute top-6 right-6 w-12 h-12 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center text-gray-800 hover:bg-pink-500 hover:text-white transition-all z-30 shadow-xl">
            <i class="fas fa-times text-xl"></i>
        </button>

        <div class="w-full md:w-[65%] h-[50vh] md:h-full bg-[#f8f8f8] flex flex-col md:flex-row relative">
            <div class="hidden md:flex flex-col gap-4 p-6 border-r border-gray-100 bg-white/50 w-24">
                <img src="" id="thumb1" onclick="changeMainImage(this.src)" class="w-full aspect-square object-cover rounded-xl cursor-pointer border-2 border-pink-400 hover:opacity-80 transition">
                <div class="w-full aspect-square bg-gray-200 rounded-xl flex items-center justify-center text-gray-400 text-xs text-center p-1">มุมอื่น 1</div>
                <div class="w-full aspect-square bg-gray-200 rounded-xl flex items-center justify-center text-gray-400 text-xs text-center p-1">มุมอื่น 2</div>
            </div>

            <div class="flex-grow relative overflow-hidden group">
                <img id="modalImage" src="" class="w-full h-full object-contain md:object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute bottom-6 right-6 bg-white/80 p-3 rounded-full text-gray-600 shadow-lg lg:block hidden">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
        </div>

        <div class="w-full md:w-[35%] p-8 md:p-12 flex flex-col bg-white overflow-y-auto">
            <div class="mb-8">
                <span id="modalTag" class="bg-pink-500 text-white text-[10px] font-bold px-4 py-1 rounded-full uppercase tracking-[0.2em] mb-4 inline-block shadow-sm"></span>
                <h2 id="modalName" class="text-3xl md:text-4xl font-bold text-gray-800 mb-3 tracking-tight"></h2>
                <div class="flex items-center gap-2 mb-6">
                    <div class="flex text-yellow-400 text-sm">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <span class="text-gray-400 text-xs">(รีวิวจากลูกค้า 20+)</span>
                </div>
                <p id="modalPrice" class="text-pink-500 font-black text-4xl mb-8"></p>
                
                <div class="space-y-6 border-t border-gray-50 pt-6">
                    <div>
                        <h4 class="text-gray-900 text-sm font-bold mb-3">รายละเอียดสินค้า</h4>
                        <p id="modalDescription" class="text-gray-500 leading-relaxed text-base"></p>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <h4 class="text-gray-900 text-[10px] font-bold uppercase mb-2">เลือกสี</h4>
                            <div class="flex gap-2">
                                <div class="w-6 h-6 rounded-full bg-pink-200 border-2 border-pink-500 cursor-pointer"></div>
                                <div class="w-6 h-6 rounded-full bg-white border border-gray-200 cursor-pointer"></div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-gray-900 text-[10px] font-bold uppercase mb-2">ไซส์</h4>
                            <div class="flex gap-2 text-[10px] font-bold">
                                <span class="px-2 py-1 border border-gray-200 rounded hover:border-pink-500 cursor-pointer transition">S</span>
                                <span class="px-2 py-1 border border-gray-200 rounded hover:border-pink-500 cursor-pointer transition">M</span>
                                <span class="px-2 py-1 border border-pink-500 text-pink-500 rounded cursor-pointer">L</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative h-64 overflow-hidden">
                <img src="<?php echo htmlspecialchars($item['image']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                
                <button onclick="event.stopPropagation(); toggleWishlist(this)" class="absolute top-4 right-4 w-10 h-10 bg-white/80 backdrop-blur-md rounded-full flex items-center justify-center text-gray-400 hover:text-pink-500 transition-all duration-300 shadow-sm z-10 group/heart">
                    <i class="fas fa-heart transition-transform group-active/heart:scale-125"></i>
                </button>

                <?php if (!empty($item['tag'])): ?>
                <span class="absolute top-4 left-4 bg-pink-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest shadow-md">
                    <?php echo htmlspecialchars($item['tag']); ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }

        const modal = document.getElementById('productModal');
        function openProductModal(product) {
            document.getElementById('modalImage').src = product.image;
            document.getElementById('modalName').innerText = product.name;
            document.getElementById('modalPrice').innerText = '฿' + parseFloat(product.price).toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('modalDescription').innerText = product.description || 'ไม่มีรายละเอียดสินค้า';
            const tagEl = document.getElementById('modalTag');
            tagEl.innerText = product.tag || '';
            tagEl.style.display = product.tag ? 'inline-block' : 'none';
            modal.classList.add('modal-active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('modal-active');
            document.body.style.overflow = 'auto';
        }
        function openProductModal(product) {
            document.getElementById('modalImage').src = product.image;
            document.getElementById('thumb1').src = product.image; // ตั้งรูป Thumbnail แรกเป็นรูปหลัก
            document.getElementById('modalName').innerText = product.name;
            document.getElementById('modalPrice').innerText = '฿' + parseFloat(product.price).toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('modalDescription').innerText = product.description || 'ดีไซน์ทันสมัย เนื้อผ้าพรีเมียม สวมใส่สบาย เหมาะสำหรับทุกโอกาสสำคัญของคุณ';
            
            const tagEl = document.getElementById('modalTag');
            tagEl.innerText = product.tag || 'NEW ARRIVAL';
            tagEl.style.display = 'inline-block';
            
            modal.classList.add('modal-active');
            document.body.style.overflow = 'hidden';
        }

        function changeMainImage(src) {
            document.getElementById('modalImage').src = src;
        }
        function toggleFavorite(button, productId) {
            const formData = new FormData();
            formData.append('product_id', productId);

            fetch('save_favorite.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                    button.classList.add('heart-active');
                } else if (data.status === 'removed') {
                    button.classList.remove('heart-active');
                    
                    // ถ้าอยู่ในหน้า Favorite.php ให้ลบ Card ออกด้วย (ถ้าต้องการ)
                    if(window.location.pathname.includes('Favorite.php')) {
                        const card = button.closest('.product-card');
                        card.style.opacity = '0';
                        setTimeout(() => card.remove(), 300);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>