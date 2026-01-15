<?php
session_start();
include('server.php'); // เชื่อมต่อฐานข้อมูล
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก | Cute App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #fff5f5; }
        .bear-card { background: rgba(255, 255, 255, 0.95); border-radius: 2rem; box-shadow: 0 20px 40px rgba(255, 182, 193, 0.4); }
        .bear-svg { width: 130px; height: 130px; transition: all 0.3s ease; }
        .input-cute { background-color: #fff0f3; border: 2px solid transparent; transition: all 0.3s ease; }
        .input-cute:focus { border-color: #ffb6c1; background-color: #ffffff; outline: none; box-shadow: 0 0 10px rgba(255, 182, 193, 0.3); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-sm">
        <div class="flex justify-center mb-[-20px] relative z-10">
            <svg id="bear" class="bear-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <circle cx="30" cy="30" r="10" fill="#a67c52" />
                <circle cx="70" cy="30" r="10" fill="#a67c52" />
                <circle cx="50" cy="55" r="35" fill="#c49a6c" />
                <ellipse cx="50" cy="65" rx="12" ry="10" fill="#e8d3bc" />
                <circle cx="50" cy="62" r="3" fill="#333" />
                <g id="eyes">
                    <circle cx="40" cy="52" r="3" fill="#333" />
                    <circle cx="60" cy="52" r="3" fill="#333" />
                </g>
                <g id="paws" style="visibility: hidden;">
                    <circle cx="40" cy="45" r="8" fill="#a67c52" />
                    <circle cx="60" cy="45" r="8" fill="#a67c52" />
                </g>
            </svg>
        </div>

        <div class="bear-card p-8 pt-12">
            <h2 class="text-2xl font-bold text-center text-pink-500 mb-6">สมัครสมาชิก</h2>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-500 rounded-xl text-sm text-center">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="register_db.php" method="POST" class="space-y-5">
                <div>
                    <label class="text-gray-500 text-sm ml-2">ชื่อผู้ใช้งาน</label>
                    <input type="text" name="username" id="username" required
                           class="input-cute w-full px-5 py-3 rounded-2xl text-gray-700"
                           placeholder="ตั้งชื่อผู้ใช้งาน...">
                </div>

                <div>
                    <label class="text-gray-500 text-sm ml-2">รหัสผ่าน</label>
                    <input type="password" name="password_1" id="password" required
                           class="input-cute w-full px-5 py-3 rounded-2xl text-gray-700"
                           placeholder="••••••••">
                </div>

                <div>
                    <label class="text-gray-500 text-sm ml-2">ยืนยันรหัสผ่าน</label>
                    <input type="password" name="password_2" id="confirm_password" required
                           class="input-cute w-full px-5 py-3 rounded-2xl text-gray-700"
                           placeholder="••••••••">
                </div>

                <button type="submit" name="reg_user"
                        class="w-full py-4 bg-pink-400 hover:bg-pink-500 text-white rounded-2xl font-bold shadow-lg transform transition active:scale-95">
                    ลงชื่อเข้าใช้
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-400">
                มีบัญชีอยู่แล้ว? <a href="login.php" class="text-pink-400 font-bold hover:underline">เข้าสู่ระบบ</a>
            </div>
        </div>
    </div>

    <script>
        const bear = document.getElementById('bear');
        const eyes = document.getElementById('eyes');
        const paws = document.getElementById('paws');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const usernameInput = document.getElementById('username');

        // ฟังก์ชันปิดตา
        const hideEyes = () => {
            eyes.style.visibility = 'hidden';
            paws.style.visibility = 'visible';
            bear.style.transform = 'translateY(5px)';
        };

        // ฟังก์ชันเปิดตา
        const showEyes = () => {
            eyes.style.visibility = 'visible';
            paws.style.visibility = 'hidden';
            bear.style.transform = 'translateY(0)';
        };

        passwordInput.addEventListener('focus', hideEyes);
        passwordInput.addEventListener('blur', showEyes);
        confirmPasswordInput.addEventListener('focus', hideEyes);
        confirmPasswordInput.addEventListener('blur', showEyes);

        usernameInput.addEventListener('focus', () => { bear.style.transform = 'rotate(-8deg)'; });
        usernameInput.addEventListener('blur', () => { bear.style.transform = 'rotate(0deg)'; });
    </script>
</body>
</html>