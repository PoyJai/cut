<?php
session_start();
include('server.php');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN | THE CORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syncopate:wght@700&family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background-color: #050505; 
            color: white; 
            font-family: 'Kanit', sans-serif;
            background-image: radial-gradient(circle at 50% -20%, #2a0a1a 0%, #050505 80%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .font-sync { font-family: 'Syncopate', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 2rem;
        }
        .input-dark {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .input-dark:focus {
            border-color: #ff007a;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 15px rgba(255, 0, 122, 0.2);
            outline: none;
        }
    </style>
</head>
<body>
    <div class="w-full max-w-md px-6 py-12">
        <div class="text-center mb-10">
            <h1 class="font-sync text-4xl tracking-tighter mb-2">TOY LAND</h1>
            <p class="text-gray-500 text-xs tracking-[0.3em] uppercase">Authentication System</p>
        </div>

        <div class="glass-card p-8 md:p-10 shadow-2xl">
            <h2 class="text-xl font-bold mb-8 text-center uppercase tracking-widest text-white/90">Sign In</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500/10 border border-red-500/50 text-red-500 p-4 rounded-xl mb-6 text-xs text-center">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="login_db.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1">Username</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
                        <input type="text" name="username" required 
                            class="input-dark w-full pl-11 pr-5 py-4 rounded-xl text-white placeholder-gray-700 text-sm"
                            placeholder="กรุณากรอกชื่อผู้ใช้">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-[0.2em] text-gray-400 mb-2 ml-1">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
                        <input type="password" name="password" required 
                            class="input-dark w-full pl-11 pr-5 py-4 rounded-xl text-white placeholder-gray-700 text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" name="login_user" 
                    class="w-full bg-white text-black font-bold py-4 rounded-xl hover:bg-[#ff007a] hover:text-white transition-all duration-300 transform active:scale-95 uppercase tracking-widest text-xs shadow-lg hover:shadow-[#ff007a]/40 mt-4">
                    Access Collection
                </button>
            </form>

            <div class="mt-10 text-center pt-6 border-t border-white/5">
                <p class="text-gray-500 text-xs">
                    ยังไม่มีบัญชีสมาชิก? 
                    <a href="register.php" class="text-white hover:text-[#ff007a] font-bold transition-colors ml-1 uppercase">สมัครสมาชิกที่นี่</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>