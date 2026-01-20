<?php
session_start();
include('server.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// ตรวจสอบว่ามีอยู่แล้วหรือไม่
$check = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
$check->execute([$user_id, $product_id]);

if ($check->fetch()) {
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    echo json_encode(['status' => 'removed']);
} else {
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $product_id]);
    echo json_encode(['status' => 'added']);
}
?>