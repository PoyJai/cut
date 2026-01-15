$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT p.* FROM products p 
                       JOIN favorites f ON p.id = f.product_id 
                       WHERE f.user_id = ?");
$stmt->execute([$user_id]);
$favorite_items = $stmt->fetchAll();
