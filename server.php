<?php
/**
 * db_config.php - ไฟล์สำหรับการเชื่อมต่อฐานข้อมูลกลาง
 */

$host = 'localhost';
$dbname = 'cute_app_db';
$db_user = 'root';      // แก้ไขตาม username ของคุณ (ปกติคือ root)
$db_pass = '';          // แก้ไขตาม password ของคุณ (ปกติคือว่างไว้)

try {
    // สร้างการเชื่อมต่อด้วย PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    
    // ตั้งค่า Error Mode ให้แสดง Exception เมื่อเกิดข้อผิดพลาด
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ตั้งค่า Fetch Mode เริ่มต้นให้เป็นแบบ associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // หากเชื่อมต่อไม่ได้ ให้ส่ง JSON error กลับไป (กรณีเรียกผ่าน AJAX)
    header('Content-Type: application/json');
    die(json_encode([
        'status' => 'error', 
        'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้: ' . $e->getMessage()
    ]));
}
?>