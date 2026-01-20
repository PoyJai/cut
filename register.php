<?php
session_start();
include('server.php');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER | THE CORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syncopate:wght@700&family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background-color: #050505; 
            color: white; 
            font-family: 'Kanit', sans-serif;
            background-image: radial-gradient(circle at 50% 120%, #1a0a2a 0%, #050505 80%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .font-sync { font-family: 'Syncopate', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 2.5rem;
        }
        .input-dark {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .input-dark:focus {
            border-color: #7000ff;
            box-shadow: 0 0 20px rgba(112, 0, 255, 0.15);
            outline: none;
        }
    </style>
</head>
<body class="py-12">
    <div class="w-full max-w-md px-6">
        <div class="text-center mb-8">
            <h1 class="font-sync text-4xl tracking-tighter mb-2">TOY LAND</h1>
            <p class="text-gray-500 text-[10px] tracking-[0.4em] uppercase">Create New Account</p>
        </div>

        <div class="glass-card p-8 md:p-10 shadow-2xl">
            <h2 class="text-lg font-bold mb-8 text-center uppercase tracking-[0.2em] text-white/80">Join Us</h2>

            <form action="register_db.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2 ml-1">Username</label>
                    <input type="text" name="username" required 
                        class="input-dark w-full px-5 py-4 rounded-xl text-white text-sm"
                        placeholder="ชื่อผู้ใช้">
                </div>

                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2 ml-1">Email Address</label>
                    <input type="email" name="email" required 
                        class="input-dark w-full px-5 py-4 rounded-xl text-white text-sm"
                        placeholder="your@email.com">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2 ml-1">Password</label>
                        <input type="password" name="password_1" required 
                            class="input-dark w-full px-5 py-4 rounded-xl text-white text-sm"
                            placeholder="••••••">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2 ml-1">Confirm</label>
                        <input type="password" name="password_2" required 
                            class="input-dark w-full px-5 py-4 rounded-xl text-white text-sm"
                            placeholder="••••••">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" name="reg_user" 
                        class="w-full bg-gradient-to-r from-[#ff007a] to-[#7000ff] text-white font-bold py-4 rounded-xl hover:opacity-90 transition-all transform active:scale-95 uppercase tracking-[0.2em] text-[11px] shadow-xl">
                        Register Now
                    </button>
                </div>
            </form>

            <div class="mt-10 text-center pt-6 border-t border-white/5">
                <p class="text-gray-500 text-xs">
                    เป็นสมาชิกอยู่แล้ว? 
                    <a href="login.php" class="text-white hover:text-pink-500 font-bold transition-colors ml-1 uppercase">Sign In</a>
                </p>
            </div>
        </div>
        
        <p class="text-center text-[9px] text-gray-700 mt-10 tracking-[0.5em] uppercase">© THE CORE COLLECTION SYSTEM</p>
    </div>
</body>
</html>