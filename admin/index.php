<?php
/**
 * الصفحة الرئيسية - لوحة التحكم
 */
$pageTitle = 'الرئيسية';
require_once 'includes/header.php';
require_once '../app/config/database.php';

$db = getDB();

// إحصائيات سريعة
$stats = [
    'categories' => 0,
    'subcategories' => 0,
    'products' => 0,
    'active_products' => 0
];

if ($db) {
    try {
        // عدد الفئات
        $stmt = $db->query("SELECT COUNT(*) FROM categories");
        $stats['categories'] = $stmt->fetchColumn();
        
        // عدد الأقسام
        $stmt = $db->query("SELECT COUNT(*) FROM subcategories");
        $stats['subcategories'] = $stmt->fetchColumn();
        
        // عدد المنتجات
        $stmt = $db->query("SELECT COUNT(*) FROM products");
        $stats['products'] = $stmt->fetchColumn();
        
        // المنتجات النشطة
        $stmt = $db->query("SELECT COUNT(*) FROM products WHERE is_active = 1");
        $stats['active_products'] = $stmt->fetchColumn();
        
        // آخر المنتجات
        $stmt = $db->query("
            SELECT p.*, s.name as subcategory_name, c.name as category_name
            FROM products p
            JOIN subcategories s ON p.subcategory_id = s.id
            JOIN categories c ON s.category_id = c.id
            ORDER BY p.created_at DESC
            LIMIT 5
        ");
        $recentProducts = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Dashboard Error: " . $e->getMessage());
    }
}
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo number_format($stats['categories']); ?></div>
            <div class="stat-label">الفئات الرئيسية</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-folder-tree"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo number_format($stats['subcategories']); ?></div>
            <div class="stat-label">الأقسام الفرعية</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-gifts"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo number_format($stats['products']); ?></div>
            <div class="stat-label">إجمالي المنتجات</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa-solid fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-value"><?php echo number_format($stats['active_products']); ?></div>
            <div class="stat-label">المنتجات النشطة</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h2 class="card-title">إجراءات سريعة</h2>
    </div>
    <div class="card-body" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="categories.php?action=add" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            إضافة فئة
        </a>
        <a href="subcategories.php?action=add" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            إضافة قسم
        </a>
        <a href="products.php?action=add" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            إضافة منتج
        </a>
        <a href="header-settings.php" class="btn btn-secondary">
            <i class="fa-solid fa-heading"></i>
            فئات رأس الموقع
        </a>
        <a href="../index.php" target="_blank" class="btn btn-secondary">
            <i class="fa-solid fa-external-link"></i>
            عرض الموقع
        </a>
    </div>
</div>

<!-- Recent Products -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">آخر المنتجات المضافة</h2>
        <a href="products.php" class="btn btn-sm btn-secondary">عرض الكل</a>
    </div>
    <div class="card-body">
        <?php if (!empty($recentProducts)): ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>المنتج</th>
                            <th>القسم</th>
                            <th>الفئة</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentProducts as $product): ?>
                            <tr>
                                <td data-label="المنتج">
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                </td>
                                <td data-label="القسم"><?php echo htmlspecialchars($product['subcategory_name']); ?></td>
                                <td data-label="الفئة"><?php echo htmlspecialchars($product['category_name']); ?></td>
                                <td data-label="الحالة">
                                    <?php if ($product['is_active']): ?>
                                        <span class="badge badge-success">نشط</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">معطل</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="التاريخ"><?php echo date('Y/m/d', strtotime($product['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-box-open"></i>
                <h3>لا توجد منتجات بعد</h3>
                <p>ابدأ بإضافة منتجات جديدة لمتجرك</p>
                <a href="products.php?action=add" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    إضافة منتج
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>