<?php
session_start();
include('server.php'); 

if (isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // ตรวจสอบทั้งแบบ Hash และแบบข้อความธรรมดา (เพื่อความปลอดภัยควรใช้ hash_verify ในอนาคต)
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("location: index.php");
        } else {
            $_SESSION['error'] = "รหัสผ่านไม่ถูกต้อง";
            header("location: login.php");
        }
    } else {
        $_SESSION['error'] = "ไม่พบชื่อผู้ใช้นี้";
        header("location: login.php");
    }
}
?>