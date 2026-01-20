<?php
session_start();
require_once('server.php');
if (!isset($_SESSION['loggedin'])) { header("location: login.php"); exit; }

$category_filter = $_GET['type'] ?? '';
// ดึงหมวดหมู่ทั้งหมด
$cat_stmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL");
$all_categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

// ดึงสินค้าตามหมวดหมู่
$sql = "SELECT * FROM products";
if ($category_filter) $sql .= " WHERE category = :cat";
$stmt = $pdo->prepare($sql);
if ($category_filter) $stmt->execute(['cat' => $category_filter]);
else $stmt->execute();
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>COLLECTION | THE CORE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syncopate:wght@700&family=Kanit:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #050505; color: white; font-family: 'Kanit', sans-serif; }
        .font-sync { font-family: 'Syncopate', sans-serif; }
        .neon-border { border: 1px solid rgba(255, 0, 122, 0.1); }
        .neon-border:hover { border-color: #ff007a; box-shadow: 0 0 15px rgba(255, 0, 122, 0.2); }
    </style>
</head>
<body class="bg-[#fffafa]">
    <div class="flex flex-col md:flex-row gap-8 max-w-7xl mx-auto p-8">
        <aside class="w-full md:w-64 space-y-4">
            <div class="bg-white p-6 rounded-[2rem] border-2 border-pink-50">
                <h3 class="font-itim text-2xl text-pink-400 mb-6 border-b-2 border-pink-50 pb-2">เลือกประเภท 🎀</h3>
                <div class="flex flex-col gap-3">
                    <a href="category.php" class="px-4 py-2 rounded-xl hover:bg-pink-50 transition">ทั้งหมด 🧸</a>
                    <?php foreach($all_categories as $cat): ?>
                        <a href="?type=<?php echo $cat; ?>" class="px-4 py-2 rounded-xl hover:bg-pink-50 transition">
                            <?php echo $cat; ?> ✨
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>
        
        </div>
</body>
</html>