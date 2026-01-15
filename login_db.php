<?php
session_start();
include('server.php'); 

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

if (isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header("location: login.php");
        exit;
    }

    try {
        // แก้ไข: เพิ่ม 'id' เข้าไปใน SELECT เพื่อให้นำไปใช้งานต่อได้
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                
                // --- จุดสำคัญที่เพิ่มเข้ามา ---
                $_SESSION['user_id'] = $user['id']; 
                // --------------------------

                header("location: index.php");
                exit;
            } else {
                $_SESSION['error'] = "รหัสผ่านไม่ถูกต้อง";
            }
        } else {
            $_SESSION['error'] = "ไม่พบชื่อผู้ใช้งาน";
        }
        header("location: login.php");
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}