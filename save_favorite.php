<?php
session_start();
require('server.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'กรุณาล็อกอินก่อน']);
    exit;
}

if (isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    try {
        // ตรวจสอบก่อนว่าเคยเพิ่มไปหรือยัง
        $check = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
        $check->execute([$user_id, $product_id]);
        
        if ($check->fetch()) {
            // ถ้ามีแล้ว ให้ลบออก (Toggle)
            $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            echo json_encode(['status' => 'removed', 'message' => 'ลบจากรายการที่ชอบแล้ว']);
        } else {
            // ถ้ายังไม่มี ให้เพิ่มเข้าตาราง
            $stmt = $pdo->prepare("INSERT INTO favorites (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $product_id]);
            echo json_encode(['status' => 'added', 'message' => 'บันทึกลงรายการที่ชอบแล้ว']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}