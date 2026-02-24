<?php
/**
 * إدارة الأقسام الفرعية
 */
require_once '../app/config/database.php';
require_once 'includes/auth.php';
requireLogin();

$db = getDB();
$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$error = '';
$subcategory = null;

// حذف - يجب أن يكون قبل أي output
if ($action === 'delete' && $editId) {
    try {
        $stmt = $db->prepare("DELETE FROM subcategories WHERE id = ?");
        $stmt->execute([$editId]);
        setFlashMessage('success', 'تم حذف القسم بنجاح');
    } catch (PDOException $e) {
        setFlashMessage('error', 'تعذر حذف القسم. قد يحتوي على منتجات.');
    }
    header('Location: subcategories.php');
    exit;
}

// معالجة POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = (int)($_POST['category_id'] ?? 0);
    $name = cleanInput($_POST['name'] ?? '');
    $icon = cleanInput($_POST['icon'] ?? 'fa-solid fa-box');
    $description = cleanInput($_POST['description'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($name) || $category_id === 0) {
        $error = 'يرجى ملء جميع الحقول المطلوبة';
    } else {
        $slug = createSlug($name);
        
   try {
    if ($action === 'edit' && $editId) {
        $stmt = $db->prepare("
            UPDATE subcategories 
            SET category_id = ?, name = ?, slug = ?, icon = ?, description = ?, sort_order = ?, is_active = ?
            WHERE id = ?
        ");
        $stmt->execute([$category_id, $name, $slug, $icon, $description, $sort_order, $is_active, $editId]);
        setFlashMessage('success', 'تم تحديث القسم بنجاح');
    } else {
        $stmt = $db->prepare("
            INSERT INTO subcategories (category_id, name, slug, icon, description, sort_order, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$category_id, $name, $slug, $icon, $description, $sort_order, $is_active]);
        setFlashMessage('success', 'تم إضافة القسم بنجاح');
    }
    header('Location: subcategories.php');
    exit;
} catch (PDOException $e) {
            $error = 'حدث خطأ أثناء الحفظ';
            error_log($e->getMessage());
        }
    }
}

// جلب بيانات التعديل
if ($action === 'edit' && $editId) {
    $stmt = $db->prepare("SELECT * FROM subcategories WHERE id = ?");
    $stmt->execute([$editId]);
    $subcategory = $stmt->fetch();
    if (!$subcategory) {
        header('Location: subcategories.php');
        exit;
    }
}

// الآن يمكن تضمين الهيدر
$pageTitle = 'الأقسام الفرعية';
require_once 'includes/header.php';

// جلب الفئات للقائمة المنسدلة
$categoriesStmt = $db->query("SELECT id, name FROM categories WHERE is_active = 1 ORDER BY sort_order");
$allCategories = $categoriesStmt->fetchAll();

// جلب جميع الأقسام
$subcategories = [];
if ($action === 'list') {
    $query = "
        SELECT s.*, c.name as category_name,
               (SELECT COUNT(*) FROM products WHERE subcategory_id = s.id) as products_count
        FROM subcategories s
        JOIN categories c ON s.category_id = c.id
    ";
    
    if ($categoryFilter > 0) {
        $query .= " WHERE s.category_id = " . $categoryFilter;
    }
    
    $query .= " ORDER BY c.sort_order, s.sort_order, s.id";
    $stmt = $db->query($query);
    $subcategories = $stmt->fetchAll();
}
?>

<?php if ($action === 'list'): ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fa-solid fa-folder-tree"></i>
                الأقسام الفرعية
            </h2>
            <div style="display: flex; gap: 12px; align-items: center;">
                <select class="form-select" style="width: auto;" onchange="filterByCategory(this.value)">
                    <option value="0">جميع الفئات</option>
                    <?php foreach ($allCategories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                                <?php echo $categoryFilter === $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <a href="?action=add" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    إضافة قسم
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($subcategories)): ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>الأيقونة</th>
                                <th>اسم القسم</th>
                                <th>الفئة</th>
                                <th>المنتجات</th>
                                <th>الترتيب</th>
                                <th>الحالة</th>
                                <th style="width: 150px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subcategories as $sub): ?>
                                <tr>
                                    <td data-label="#"><?php echo $sub['id']; ?></td>
                                    <td data-label="الأيقونة">
                                        <span style="font-size: 18px; color: var(--primary);">
                                            <i class="<?php echo htmlspecialchars($sub['icon']); ?>"></i>
                                        </span>
                                    </td>
                                    <td data-label="اسم القسم">
                                        <strong><?php echo htmlspecialchars($sub['name']); ?></strong>
                                        <br>
                                        <small style="color: var(--text-muted);"><?php echo $sub['slug']; ?></small>
                                    </td>
                                    <td data-label="الفئة">
                                        <span class="badge badge-success">
                                            <?php echo htmlspecialchars($sub['category_name']); ?>
                                        </span>
                                    </td>
                                    <td data-label="المنتجات"><?php echo $sub['products_count']; ?> منتج</td>
                                    <td data-label="الترتيب"><?php echo $sub['sort_order']; ?></td>
                                    <td data-label="الحالة">
                                        <?php if ($sub['is_active']): ?>
                                            <span class="badge badge-success">نشط</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">معطل</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="الإجراءات">
                                        <div class="actions">
                                            <a href="?action=edit&id=<?php echo $sub['id']; ?>" 
                                               class="btn btn-sm btn-secondary" title="تعديل">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $sub['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               data-confirm="هل أنت متأكد من حذف هذا القسم؟"
                                               data-delete
                                               title="حذف">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fa-solid fa-folder-open"></i>
                    <h3>لا توجد أقسام</h3>
                    <p>ابدأ بإضافة قسم جديد</p>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        إضافة قسم
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function filterByCategory(id) {
            window.location.href = 'subcategories.php' + (id > 0 ? '?category=' + id : '');
        }
    </script>

<?php else: ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fa-solid fa-<?php echo $action === 'edit' ? 'pen' : 'plus'; ?>"></i>
                <?php echo $action === 'edit' ? 'تعديل القسم' : 'إضافة قسم جديد'; ?>
            </h2>
            <a href="subcategories.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-right"></i>
                رجوع
            </a>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($allCategories)): ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    يجب إضافة فئة رئيسية أولاً قبل إضافة أقسام فرعية.
                    <a href="categories.php?action=add">إضافة فئة</a>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                الفئة الرئيسية <span class="required">*</span>
                            </label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- اختر الفئة --</option>
                                <?php foreach ($allCategories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"
                                            <?php echo ($subcategory['category_id'] ?? 0) == $cat['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                اسم القسم <span class="required">*</span>
                            </label>
                            <input type="text" name="name" class="form-input" 
                                   value="<?php echo htmlspecialchars($subcategory['name'] ?? ''); ?>"
                                   placeholder="مثال: هدايا عيد ميلاد" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">أيقونة Font Awesome</label>
                        <input type="text" name="icon" class="form-input" 
                               value="<?php echo htmlspecialchars($subcategory['icon'] ?? 'fa-solid fa-box'); ?>"
                               placeholder="fa-solid fa-box">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">الوصف (اختياري)</label>
                        <textarea name="description" class="form-textarea" 
                                  placeholder="وصف مختصر للقسم..."><?php echo htmlspecialchars($subcategory['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">الترتيب</label>
                            <input type="number" name="sort_order" class="form-input" 
                                   value="<?php echo $subcategory['sort_order'] ?? 0; ?>" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">الحالة</label>
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-top: 8px;">
                                <input type="checkbox" name="is_active" value="1"
                                       <?php echo (!$subcategory || $subcategory['is_active']) ? 'checked' : ''; ?>
                                       style="width: 20px; height: 20px;">
                                <span>نشط (يظهر في الموقع)</span>
                            </label>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i>
                            <?php echo $action === 'edit' ? 'حفظ التغييرات' : 'إضافة القسم'; ?>
                        </button>
                        <a href="subcategories.php" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>