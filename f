// --- เพิ่มส่วนนี้เข้าไปครับ ---
$items_per_page = 8; // กำหนดจำนวนสินค้าต่อหน้า
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $items_per_page;

// ดึงจำนวนสินค้าทั้งหมดเพื่อคำนวณหน้า (Pagination)
$stmt_total = $pdo->query("SELECT COUNT(*) FROM products");
$total_rows = $stmt_total->fetchColumn();
$total_pages = ceil($total_rows / $items_per_page);

// ดึงข้อมูลสินค้า (ย้ายจากใน if(isset) ออกมาไว้ข้างนอกเพื่อให้โหลดหน้าแรกได้)
$stmt = $pdo->prepare("
    SELECT p.*, f.id AS is_favorite 
    FROM products p 
    LEFT JOIN favorites f ON p.id = f.product_id AND f.user_id = :user_id 
    ORDER BY p.id DESC LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':user_id', $_SESSION['user_id'] ?? 0, PDO::PARAM_INT);
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();
// --------------------------
