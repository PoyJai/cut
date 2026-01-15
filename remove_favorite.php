<?php
session_start();
require('server.php');

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
    if ($stmt->execute([$user_id, $product_id])) {
        echo json_encode(['status' => 'success']);
    }
}