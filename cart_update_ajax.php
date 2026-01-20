<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $p_id = $_POST['product_id'];
    $action = $_POST['action'] ?? '';

    if (isset($_SESSION['cart'][$p_id])) {
        if ($action === 'remove') {
            // ลบสินค้าออกทันที
            unset($_SESSION['cart'][$p_id]);
        } else {
            // ส่วนเดิม: การปรับจำนวน (delta)
            $delta = (int)$_POST['delta'];
            $_SESSION['cart'][$p_id] += $delta;
            if ($_SESSION['cart'][$p_id] <= 0) unset($_SESSION['cart'][$p_id]);
        }
        
        echo json_encode([
            'success' => true, 
            'cart_empty' => empty($_SESSION['cart'])
        ]);
    }
    exit;
}