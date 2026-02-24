<?php
/**
 * إدارة الفئات الرئيسية
 */
require_once '../app/config/database.php';
require_once 'includes/auth.php';
requireLogin();

$db = getDB();
$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$category = null;

// حذف - يجب أن يكون قبل أي output
if ($action === 'delete' && $editId) {
    try {
        $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$editId]);
        setFlashMessage('success', 'تم حذف الفئة بنجاح');
    } catch (PDOException $e) {
        setFlashMessage('error', 'تعذر حذف الفئة. قد تحتوي على أقسام فرعية.');
    }
    header('Location: categories.php');
    exit;
}

// معالجة POST - يجب أن يكون قبل أي output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = cleanInput($_POST['name'] ?? '');
    $icon = cleanInput($_POST['icon'] ?? 'fa-solid fa-gift');
    $description = cleanInput($_POST['description'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($name)) {
        $error = 'يرجى إدخال اسم الفئة';
    } else {
      $slug = createSlug($name);        
     try {
    if ($action === 'edit' && $editId) {
        // تحديث
        $stmt = $db->prepare("
            UPDATE categories 
            SET name = ?, slug = ?, icon = ?, description = ?, sort_order = ?, is_active = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $slug, $icon, $description, $sort_order, $is_active, $editId]);
        setFlashMessage('success', 'تم تحديث الفئة بنجاح');
    } else {
        // إضافة
        $stmt = $db->prepare("
            INSERT INTO categories (name, slug, icon, description, sort_order, is_active)
            VALUES (?, ?, ?, ?, ?, ?) 
        ");
        $stmt->execute([$name, $slug, $icon, $description, $sort_order, $is_active]);
        setFlashMessage('success', 'تم إضافة الفئة بنجاح');
    }
    header('Location: categories.php');
    exit;
} catch (PDOException $e) {
    $error = 'حدث خطأ أثناء الحفظ';
    error_log($e->getMessage());
} 
    }
}

// جلب بيانات التعديل
if ($action === 'edit' && $editId) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$editId]);
    $category = $stmt->fetch();
    if (!$category) {
        header('Location: categories.php');
        exit;
    }
}

// الآن يمكن تضمين الهيدر
$pageTitle = 'الفئات الرئيسية';
require_once 'includes/header.php';

// جلب جميع الفئات
$categories = [];
if ($action === 'list') {
    $stmt = $db->query("
        SELECT c.*, 
               (SELECT COUNT(*) FROM subcategories WHERE category_id = c.id) as subcategories_count
        FROM categories c
        ORDER BY c.sort_order, c.id
    ");
    $categories = $stmt->fetchAll();
}
?>

<?php if ($action === 'list'): ?>
    <!-- قائمة الفئات -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fa-solid fa-layer-group"></i>
                الفئات الرئيسية
            </h2>
            <a href="?action=add" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                إضافة فئة
            </a>
        </div>
        <div class="card-body">
            <?php if (!empty($categories)): ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>الأيقونة</th>
                                <th>اسم الفئة</th>
                                <th>الأقسام</th>
                                <th>الترتيب</th>
                                <th>الحالة</th>
                                <th style="width: 150px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td data-label="#"><?php echo $cat['id']; ?></td>
                                    <td data-label="الأيقونة">
                                        <span style="font-size: 20px; color: var(--primary);">
                                            <i class="<?php echo htmlspecialchars($cat['icon']); ?>"></i>
                                        </span>
                                    </td>
                                    <td data-label="اسم الفئة">
                                        <strong><?php echo htmlspecialchars($cat['name']); ?></strong>
                                        <br>
                                        <small style="color: var(--text-muted);"><?php echo $cat['slug']; ?></small>
                                    </td>
                                    <td data-label="الأقسام">
                                        <span class="badge badge-<?php echo $cat['subcategories_count'] > 0 ? 'success' : 'warning'; ?>">
                                            <?php echo $cat['subcategories_count']; ?> قسم
                                        </span>
                                    </td>
                                    <td data-label="الترتيب"><?php echo $cat['sort_order']; ?></td>
                                    <td data-label="الحالة">
                                        <?php if ($cat['is_active']): ?>
                                            <span class="badge badge-success">نشط</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">معطل</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="الإجراءات">
                                        <div class="actions">
                                            <a href="?action=edit&id=<?php echo $cat['id']; ?>" 
                                               class="btn btn-sm btn-secondary" title="تعديل">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $cat['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               data-confirm="هل أنت متأكد من حذف هذه الفئة؟"
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
                    <i class="fa-solid fa-layer-group"></i>
                    <h3>لا توجد فئات</h3>
                    <p>ابدأ بإضافة فئة جديدة</p>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        إضافة فئة
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>
    <!-- نموذج الإضافة/التعديل -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fa-solid fa-<?php echo $action === 'edit' ? 'pen' : 'plus'; ?>"></i>
                <?php echo $action === 'edit' ? 'تعديل الفئة' : 'إضافة فئة جديدة'; ?>
            </h2>
            <a href="categories.php" class="btn btn-secondary">
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
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            اسم الفئة <span class="required">*</span>
                        </label>
                        <input type="text" name="name" class="form-input" 
                               value="<?php echo htmlspecialchars($category['name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">أيقونة Font Awesome</label>
                        <input type="text" name="icon" class="form-input" 
                               value="<?php echo htmlspecialchars($category['icon'] ?? 'fa-solid fa-gift'); ?>"
                               placeholder="fa-solid fa-gift">
                        <div class="form-hint">
                            استخدم أيقونات <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">الوصف (اختياري)</label>
                    <textarea name="description" class="form-textarea" 
                              placeholder="وصف مختصر للفئة..."><?php echo htmlspecialchars($category['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">الترتيب</label>
                        <input type="number" name="sort_order" class="form-input" 
                               value="<?php echo $category['sort_order'] ?? 0; ?>"
                               min="0">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">الحالة</label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-top: 8px;">
                            <input type="checkbox" name="is_active" value="1"
                                   <?php echo (!$category || $category['is_active']) ? 'checked' : ''; ?>
                                   style="width: 20px; height: 20px;">
                            <span>نشط (يظهر في الموقع)</span>
                        </label>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i>
                        <?php echo $action === 'edit' ? 'حفظ التغييرات' : 'إضافة الفئة'; ?>
                    </button>
                    <a href="categories.php" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>