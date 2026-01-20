<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $p_id = $_POST['product_id'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // ถ้ามีสินค้าอยู่แล้วให้เพิ่มจำนวน ถ้าไม่มีให้เริ่มที่ 1
    if (isset($_SESSION['cart'][$p_id])) {
        $_SESSION['cart'][$p_id]++;
    } else {
        $_SESSION['cart'][$p_id] = 1;
    }

    echo json_encode([
        'success' => true,
        'total_items' => array_sum($_SESSION['cart'])
    ]);
    exit;
}
?>