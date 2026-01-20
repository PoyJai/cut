<?php
session_start();
require_once('server.php');

if (!isset($_SESSION['loggedin']) || empty($_SESSION['cart'])) {
    header("location: index.php");
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. คำนวณยอดรวม
    $total_price = 0;
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT id, price FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll();
    
    foreach ($products as $p) {
        $total_price += $p['price'] * $_SESSION['cart'][$p['id']];
    }
    $grand_total = $total_price + 50; // รวมค่าส่ง

    // 2. บันทึกลงตาราง orders
    $stmt_order = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
    $stmt_order->execute([$_SESSION['user_id'], $grand_total]);
    $order_id = $pdo->lastInsertId();

    // 3. บันทึกลงตาราง order_items
    $stmt_items = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($products as $p) {
        $stmt_items->execute([
            $order_id, 
            $p['id'], 
            $_SESSION['cart'][$p['id']], 
            $p['price']
        ]);
    }

    $pdo->commit();
    
    // เก็บ order_id ไว้ใน session เพื่อไปใช้ในหน้า payment
    $_SESSION['current_order_id'] = $order_id;
    header("location: payment.php");

} catch (Exception $e) {
    $pdo->rollBack();
    die("เกิดข้อผิดพลาด: " . $e->getMessage());
}