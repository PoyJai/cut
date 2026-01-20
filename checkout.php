<?php
session_start();
require_once('server.php');

// 1. ตรวจสอบการ Login
if (!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit;
}

$cart_items = [];
$total_price = 0;
$shipping_fee = 50; // ค่าส่งแบบเหมา

// 2. ดึงข้อมูลสินค้าจากตะกร้า
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $cart_items = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน | SUNSHINE SHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #fffafb; }
        .input-field { @apply w-full px-4 py-3 rounded-2xl border border-gray-100 focus:border-pink-300 focus:ring focus:ring-pink-100 outline-none transition-all bg-gray-50; }
    </style>
</head>
<body>
    <div class="max-w-6xl mx-auto p-6 md:p-12">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 flex items-center gap-3">
            <span>🛒 ชำระเงิน</span>
        </h1>

        <form action="payment.php" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-pink-50">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-pink-500"></i> ข้อมูลการจัดส่ง
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-500 mb-2 ml-2">ชื่อ-นามสกุล ผู้รับ</label>
                            <input type="text" name="receiver_name" required class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-pink-300 focus:ring-2 focus:ring-pink-100 outline-none" placeholder="เอกสิทธิ์ ใจดี">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-500 mb-2 ml-2">ที่อยู่จัดส่งสินค้า</label>
                            <textarea name="address" required rows="3" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-pink-300 focus:ring-2 focus:ring-pink-100 outline-none" placeholder="บ้านเลขที่, ถนน, แขวง/ตำบล, เขต/อำเภอ, จังหวัด, รหัสไปรษณีย์"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-2 ml-2">เบอร์โทรศัพท์</label>
                            <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-pink-300 focus:ring-2 focus:ring-pink-100 outline-none" placeholder="08x-xxx-xxxx">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-2 ml-2">อีเมล (ถ้ามี)</label>
                            <input type="email" name="email" class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-pink-300 focus:ring-2 focus:ring-pink-100 outline-none" placeholder="example@mail.com">
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-xl font-bold px-2">รายการสินค้า</h2>
                    <?php if (empty($cart_items)): ?>
                        <div class="bg-white p-10 rounded-3xl text-center shadow-sm border border-pink-50">
                            <p class="text-gray-400">ไม่มีสินค้าในตะกร้า</p>
                            <a href="index.php" class="text-pink-500 mt-4 inline-block underline">กลับไปเลือกซื้อสินค้า</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($cart_items as $item): 
                            $qty = $_SESSION['cart'][$item['id']];
                            $subtotal = $item['price'] * $qty;
                            $total_price += $subtotal;
                        ?>
                        <div id="item-row-<?= $item['id'] ?>" class="flex items-center gap-4 bg-white p-4 rounded-3xl border border-pink-50 shadow-sm relative group">
                            <img src="<?= $item['image'] ?>" class="w-20 h-20 object-cover rounded-2xl">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-700"><?= $item['name'] ?></h4>
                                <p class="text-pink-500 font-bold">฿<?= number_format($item['price']) ?> x <?= $qty ?></p>
                            </div>
                            <div class="text-right mr-4">
                                <p class="font-bold text-gray-800">฿<?= number_format($subtotal, 2) ?></p>
                            </div>
                            <button type="button" onclick="removeItem(<?= $item['id'] ?>)" class="text-gray-300 hover:text-red-500 transition-colors p-2">
                                <i class="fas fa-trash-alt text-lg"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border-t-4 border-pink-400 h-fit sticky top-6">
                    <h2 class="text-xl font-bold mb-6 text-gray-800">สรุปยอดชำระ 💳</h2>
                    
                    <div class="space-y-3 text-gray-600 pb-6 border-b border-dashed">
                        <div class="flex justify-between">
                            <span>รวมค่าสินค้า</span>
                            <span>฿<?php echo number_format($total_price, 2); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>ค่าจัดส่ง (เหมา)</span>
                            <span>฿<?php echo number_format($shipping_fee, 2); ?></span>
                        </div>
                    </div>

                    <div class="flex justify-between py-6">
                        <span class="text-lg font-bold">ยอดสุทธิ</span>
                        <span class="text-2xl font-bold text-pink-500">
                            ฿<?php echo number_format($total_price > 0 ? $total_price + $shipping_fee : 0, 2); ?>
                        </span>
                    </div>

                    <?php if (!empty($cart_items)): ?>
                        <a href="payment.php">
                    <button type="submit" class="block w-full bg-yellow-400 hover:bg-yellow-500 text-white text-center font-bold py-4 rounded-2xl shadow-lg transition active:scale-95 text-lg">
                        ยืนยันการสั่งซื้อ 🚀
                    </button></a>
                    <?php else: ?>
                    <button type="button" disabled class="block w-full bg-gray-200 text-gray-400 text-center font-bold py-4 rounded-2xl cursor-not-allowed text-lg">
                        กรุณาเลือกสินค้า
                    </button>
                    <?php endif; ?>
                                    
                    <p class="text-[10px] text-gray-400 text-center mt-4">
                        * ตรวจสอบข้อมูลให้ถูกต้องก่อนกดยืนยัน
                    </p>
                </div>
            </div>
        </form>
    </div>

    <script>
        function removeItem(productId) {
            if (!confirm('ยืนยันที่จะลบสินค้าชิ้นนี้ออกจากตะกร้า?')) return;

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('action', 'remove');

            fetch('cart_update_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('item-row-' + productId);
                    row.style.opacity = '0';
                    setTimeout(() => {
                        location.reload(); 
                    }, 300);
                }
            });
        }
    </script>
</body>
</html>