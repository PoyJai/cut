<?php
session_start();
require_once('server.php');

if (!isset($_SESSION['loggedin'])) { header("location: login.php"); exit; }

// ดึงข้อมูลสินค้าจากตะกร้ามาคำนวณยอด
$cart_items = [];
$total_price = 0;
$shipping_fee = 50;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $cart_items = $stmt->fetchAll();
    
    foreach ($cart_items as $item) {
        $total_price += $item['price'] * $_SESSION['cart'][$item['id']];
    }
} else {
    // ถ้าตะกร้าว่างให้กลับไปหน้าแรก
    header("location: index.php");
    exit;
}

$grand_total = $total_price + $shipping_fee;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>PAYMENT | ชำระเงิน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&family=Itim&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fdf2f8; font-family: 'Kanit', sans-serif; }
        .font-itim { font-family: 'Itim', cursive; }
        .bank-card { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); }
    </style>
</head>
<body class="pb-20">

    <div class="max-w-2xl mx-auto p-6">
        <div class="text-center my-10">
            <h1 class="text-4xl font-bold text-gray-800 font-itim">ชำระเงิน 💸</h1>
            <p class="text-gray-500">กรุณาโอนเงินและแนบหลักฐานการโอน</p>
        </div>

        <div class="bank-card p-8 rounded-[2.5rem] text-white shadow-2xl mb-8 relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-8">
                    <span class="text-lg opacity-80">บัญชีสำหรับโอนเงิน</span>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c4/Savannah_Bank_Logo.png" class="h-8 filter brightness-200">
                </div>
                <div class="mb-6">
                    <p class="text-sm opacity-70">เลขบัญชี</p>
                    <p class="text-3xl font-mono tracking-wider">123-4-56789-0</p>
                </div>
                <div class="flex justify-between">
                    <div>
                        <p class="text-xs opacity-70 uppercase">Account Name</p>
                        <p class="font-bold">บจก. SUNSHINE SWEET SHOP</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs opacity-70 uppercase">Bank</p>
                        <p class="font-bold">ธนาคารกสิกรไทย</p>
                    </div>
                </div>
            </div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-xl border-2 border-pink-100 mb-8">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-500">ยอดรวมสินค้า</span>
                <span class="font-bold">฿<?php echo number_format($total_price); ?></span>
            </div>
            <div class="flex justify-between items-center mb-6">
                <span class="text-gray-500">ค่าจัดส่ง</span>
                <span class="font-bold">฿<?php echo number_format($shipping_fee); ?></span>
            </div>
            <div class="border-t-2 border-dashed border-pink-50 pt-4 flex justify-between items-center">
                <span class="text-xl font-bold">ยอดที่ต้องโอนทั้งหมด</span>
                <span class="text-3xl font-bold text-pink-500">฿<?php echo number_format($grand_total); ?></span>
            </div>
        </div>

        <form action="confirm_payment.php" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-[2rem] shadow-xl border-2 border-pink-100">
            <h3 class="font-bold text-xl mb-6 flex items-center gap-2">
                <span class="bg-pink-100 p-2 rounded-lg text-pink-500">📸</span> แนบสลิปการโอน
            </h3>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">อัปโหลดรูปภาพสลิป</label>
                <input type="file" name="slip_image" required
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 cursor-pointer border-2 border-dashed border-pink-100 p-4 rounded-2xl">
            </div>

            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">ที่อยู่จัดส่ง (ระบุชื่อ-ที่อยู่-เบอร์โทร)</label>
                <textarea name="address" rows="4" required placeholder="กรอกข้อมูลจัดส่งที่นี่..."
                    class="w-full p-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-pink-300 focus:bg-white transition outline-none"></textarea>
            </div>

            <button type="submit" 
                class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-5 rounded-2xl shadow-lg shadow-pink-200 transition transform active:scale-95 flex items-center justify-center gap-2 text-lg">
                ยืนยันการชำระเงิน <i class="fas fa-check-circle"></i>
            </button>
            
            <a href="checkout.php" class="block text-center mt-6 text-gray-400 hover:text-pink-500 transition text-sm">
                ย้อนกลับไปตะกร้าสินค้า
            </a>
        </form>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>