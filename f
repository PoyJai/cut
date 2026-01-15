// --- เพิ่มส่วนนี้ต่อจาก require('server.php'); ---
$user_id = $_SESSION['user_id'];
$fav_stmt = $pdo->prepare("SELECT product_id FROM favorites WHERE user_id = ?");
$fav_stmt->execute([$user_id]);
$user_favs = $fav_stmt->fetchAll(PDO::FETCH_COLUMN); // จะได้รายการ ID สินค้าที่ชอบเป็น Array

---------------

<button onclick="toggleWishlist(this, <?php echo $product['id']; ?>)" 
        class="absolute top-4 right-4 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-pink-500 transition-all duration-300 z-10 shadow-sm 
        <?php echo in_array($product['id'], $user_favs) ? 'heart-active' : ''; ?>">
    <i class="fas fa-heart"></i>
</button>

-------------

<?php
session_start();
require('server.php');

if (!isset($_SESSION['loggedin'])) { header("location: login.php"); exit; }

$user_id = $_SESSION['user_id'];

try {
    // ดึงข้อมูลสินค้าที่ Join กับตาราง favorites เฉพาะของ User ที่ล็อกอิน
    $query = "SELECT p.* FROM products p 
              JOIN favorites f ON p.id = f.product_id 
              WHERE f.user_id = :user_id 
              ORDER BY f.created_at DESC";
              
    $stmt = $pdo->prepare($query); 
    $stmt->execute(['user_id' => $user_id]);
    $fav_products = $stmt->fetchAll(); // ใช้ตัวแปรนี้แสดงผลด้านล่าง
} catch (PDOException $e) { 
    die("Error: " . $e->getMessage()); 
}
?>

------------


<style>
    .heart-active i {
        color: #ec4899 !important; /* สีชมพู */
    }
    .heart-active {
        background-color: #fdf2f8 !important; /* พื้นหลังสีชมพูอ่อน */
        border: 1px solid #f9a8d4 !important;
    }
</style>
