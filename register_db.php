<?php
session_start();
require('server.php'); // เชื่อมต่อฐานข้อมูลผ่าน PDO

if (isset($_POST['reg_user'])) {
    // รับค่าจากฟอร์ม
    $username = $_POST['username'];
    $password_1 = $_POST['password_1'];
    $password_2 = $_POST['password_2'];

    // 1. ตรวจสอบว่ากรอกข้อมูลครบหรือไม่
    if (empty($username) || empty($password_1) || empty($password_2)) {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
        header("location: register.php");
        exit;
    }

    // 2. ตรวจสอบว่ารหัสผ่านทั้งสองช่องตรงกันหรือไม่
    if ($password_1 !== $password_2) {
        $_SESSION['error'] = "รหัสผ่านทั้งสองช่องไม่ตรงกัน";
        header("location: register.php");
        exit;
    }

    try {
        // 3. ตรวจสอบว่ามีชื่อผู้ใช้นี้ในระบบหรือยัง
        $check_user = $pdo->prepare("SELECT username FROM users WHERE username = :username LIMIT 1");
        $check_user->bindParam(':username', $username);
        $check_user->execute();
        
        if ($check_user->fetch()) {
            $_SESSION['error'] = "ชื่อผู้ใช้งานนี้ถูกใช้ไปแล้ว";
            header("location: register.php");
            exit;
        }

        // 4. เข้ารหัสรหัสผ่าน (Hashing) ก่อนบันทึก
        $hashed_password = password_hash($password_1, PASSWORD_DEFAULT);

        // 5. บันทึกข้อมูลลงฐานข้อมูล
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['success'] = "สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ";
            header("location: login.php");
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header("location: register.php");
        exit;
    }
} else {
    header("location: register.php");
    exit;
}
?>