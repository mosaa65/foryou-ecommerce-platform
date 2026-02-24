<?php
/**
 * إدارة المنتجات
 */
require_once '../app/config/database.php';
require_once 'includes/auth.php';
requireLogin();

$db = getDB();
$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$subcategoryFilter = isset($_GET['subcategory']) ? (int)$_GET['subcategory'] : 0;
$error = '';
$product = null;

// حذف - يجب أن يكون قبل أي output
if ($action === 'delete' && $editId) {
    try {
        // جلب مسار الصورة قبل الحذف
        $stmt = $db->prepare("SELECT image_path FROM products WHERE id = ?");
        $stmt->execute([$editId]);
        $productToDelete = $stmt->fetch();
        
        // حذف المنتج
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$editId]);
        
        // حذف الصورة
        if ($productToDelete && $productToDelete['image_path'] && file_exists('../' . $productToDelete['image_path'])) {
            unlink('../' . $productToDelete['image_path']);
        }
        
        setFlashMessage('success', 'تم حذف المنتج بنجاح');
    } catch (PDOException $e) {
        setFlashMessage('error', 'تعذر حذف المنتج');
    }
    header('Location: products.php');
    exit;
}

// معالجة POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subcategory_id = (int)($_POST['subcategory_id'] ?? 0);
    $name = cleanInput($_POST['name'] ?? '');
    $description = $_POST['description'] ?? '';
    $price = !empty($_POST['price']) ? (float)$_POST['price'] : null;
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($name) || $subcategory_id === 0) {
        $error = 'يرجى ملء جميع الحقول المطلوبة';
    } else {
        $slug = createSlug($name);
        $image_path = $_POST['current_image'] ?? null;
        
        // معالجة رفع الصورة
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/products/';
            
            // إنشاء المجلد إذا لم يكن موجوداً
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileInfo = pathinfo($_FILES['image']['name']);
            $extension = strtolower($fileInfo['extension']);
            
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($extension, $allowedTypes)) {
                $newFileName = 'product_' . time() . '_' . uniqid() . '.' . $extension;
                $uploadPath = $uploadDir . $newFileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    // حذف الصورة القديمة إذا وجدت
                    if ($image_path && file_exists('../' . $image_path)) {
                        unlink('../' . $image_path);
                    }
                    $image_path = 'uploads/products/' . $newFileName;
                }
            } else {
                $error = 'نوع الملف غير مسموح. الأنواع المسموحة: ' . implode(', ', $allowedTypes);
            }
        }
        
        if (empty($error)) {
        try {
    if ($action === 'edit' && $editId) {
        $stmt = $db->prepare("
            UPDATE products 
            SET subcategory_id = ?, name = ?, slug = ?, description = ?, price = ?, 
                image_path = ?, sort_order = ?, is_active = ?
            WHERE id = ?
        ");
        $stmt->execute([$subcategory_id, $name, $slug, $description, $price, $image_path, $sort_order, $is_active, $editId]);
        setFlashMessage('success', 'تم تحديث المنتج بنجاح');
    } else {
        $stmt = $db->prepare("
            INSERT INTO products (subcategory_id, name, slug, description, price, image_path, sort_order, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$subcategory_id, $name, $slug, $description, $price, $image_path, $sort_order, $is_active]);
        setFlashMessage('success', 'تم إضافة المنتج بنجاح');
    }
    header('Location: products.php');
    exit;
} catch (PDOException $e) {
                $error = 'حدث خطأ أثناء الحفظ';
                error_log($e->getMessage());
            }
        }
    }
}

// جلب بيانات التعديل
if ($action === 'edit' && $editId) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$editId]);
    $product = $stmt->fetch();
    if (!$product) {
        header('Location: products.php');
        exit;
    }
}

// الآن يمكن تضمين الهيدر
$pageTitle = 'المنتجات';
require_once 'includes/header.php';

// جلب الأقسام للقائمة المنسدلة
$subcategoriesStmt = $db->query("
    SELECT s.id, s.name, c.name as category_name
    FROM subcategories s
    JOIN categories c ON s.category_id = c.id
    WHERE s.is_active = 1
    ORDER BY c.sort_order, s.sort_order
");
$allSubcategories = $subcategoriesStmt->fetchAll();

// جلب جميع المنتجات
$products = [];
if ($action === 'list') {
    $query = "
        SELECT p.*, s.name as subcategory_name, c.name as category_name
        FROM products p
        JOIN subcategories s ON p.subcategory_id = s.id
        JOIN categories c ON s.category_id = c.id
    ";
    
    if ($subcategoryFilter > 0) {
        $query .= " WHERE p.subcategory_id = " . $subcategoryFilter;
    }
    
    $query .= " ORDER BY p.created_at DESC";
    $stmt = $db->query($query);
    $products = $stmt->fetchAll();
}
?>

<?php if ($action === 'list'): ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fa-solid fa-gifts"></i>
                المنتجات
            </h2>
            <div style="display: flex; gap: 12px; align-items: center;">
                <select class="form-select" style="width: auto; max-width: 200px;" onchange="filterBySubcategory(this.value)">
                    <option value="0">جميع الأقسام</option>
                    <?php foreach ($allSubcategories as $sub): ?>
                        <option value="<?php echo $sub['id']; ?>" 
                                <?php echo $subcategoryFilter === $sub['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sub['category_name'] . ' > ' . $sub['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <a href="?action=add" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    إضافة منتج
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($products)): ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">الصورة</th>
                                <th>اسم المنتج</th>
                                <th>القسم</th>
                                <th>السعر</th>
                                <th>الحالة</th>
                                <th style="width: 150px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $prod): ?>
                                <tr>
                                    <td data-label="الصورة">
                                        <?php if ($prod['image_path']): ?>
                                            <img src="../<?php echo htmlspecialchars($prod['image_path']); ?>" 
                                                 alt="<?php echo htmlspecialchars($prod['name']); ?>"
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        <?php else: ?>
                                            <div style="width: 60px; height: 60px; background: var(--bg-hover); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fa-solid fa-image" style="color: var(--text-muted);"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="اسم المنتج">
                                        <strong><?php echo htmlspecialchars($prod['name']); ?></strong>
                                    </td>
                                    <td data-label="القسم">
                                        <small style="color: var(--text-muted);">
                                            <?php echo htmlspecialchars($prod['category_name']); ?>
                                        </small>
                                        <br>
                                        <?php echo htmlspecialchars($prod['subcategory_name']); ?>
                                    </td>
                                    <td data-label="السعر">
                                        <?php if ($prod['price']): ?>
                                            <strong style="color: var(--success);">
                                                <?php echo number_format($prod['price'], 2); ?> ر.س
                                            </strong>
                                        <?php else: ?>
                                            <span style="color: var(--text-muted);">غير محدد</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="الحالة">
                                        <?php if ($prod['is_active']): ?>
                                            <span class="badge badge-success">نشط</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">معطل</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="الإجراءات">
                                        <div class="actions">
                                            <a href="?action=edit&id=<?php echo $prod['id']; ?>" 
                                               class="btn btn-sm btn-secondary" title="تعديل">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="?action=delete&id=<?php echo $prod['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               data-confirm="هل أنت متأكد من حذف هذا المنتج؟"
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
                    <i class="fa-solid fa-box-open"></i>
                    <h3>لا توجد منتجات</h3>
                    <p>ابدأ بإضافة منتج جديد</p>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        إضافة منتج
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function filterBySubcategory(id) {
            window.location.href = 'products.php' + (id > 0 ? '?subcategory=' + id : '');
        }
    </script>

<?php else: ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fa-solid fa-<?php echo $action === 'edit' ? 'pen' : 'plus'; ?>"></i>
                <?php echo $action === 'edit' ? 'تعديل المنتج' : 'إضافة منتج جديد'; ?>
            </h2>
            <a href="products.php" class="btn btn-secondary">
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
            
            <?php if (empty($allSubcategories)): ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    يجب إضافة قسم فرعي أولاً قبل إضافة منتجات.
                    <a href="subcategories.php?action=add">إضافة قسم</a>
                </div>
            <?php else: ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <?php if ($product && $product['image_path']): ?>
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product['image_path']); ?>">
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                القسم <span class="required">*</span>
                            </label>
                            <select name="subcategory_id" class="form-select" required>
                                <option value="">-- اختر القسم --</option>
                                <?php foreach ($allSubcategories as $sub): ?>
                                    <option value="<?php echo $sub['id']; ?>"
                                            <?php echo ($product['subcategory_id'] ?? 0) == $sub['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($sub['category_name'] . ' > ' . $sub['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                اسم المنتج <span class="required">*</span>
                            </label>
                            <input type="text" name="name" class="form-input" 
                                   value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
                        </div>
                    
                    
                    <div class="form-group">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-textarea" rows="4"
                                  placeholder="وصف تفصيلي للمنتج..."><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">السعر (اختياري)</label>
                            <input type="number" name="price" class="form-input" step="0.01" min="0"
                                   value="<?php echo $product['price'] ?? ''; ?>"
                                   placeholder="اتركه فارغاً إذا لم تريد عرض السعر">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">الترتيب</label>
                            <input type="number" name="sort_order" class="form-input" 
                                   value="<?php echo $product['sort_order'] ?? 0; ?>" min="0">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">صورة المنتج</label>
                        <div class="image-upload">
                            <i class="fa-solid fa-cloud-upload-alt"></i>
                            <p>اضغط أو اسحب الصورة هنا</p>
                            <input type="file" name="image" accept="image/*">
                        </div>
                        <div class="image-preview" style="<?php echo ($product && $product['image_path']) ? '' : 'display:none;'; ?>">
                            <?php if ($product && $product['image_path']): ?>
                                <img src="../<?php echo htmlspecialchars($product['image_path']); ?>" alt="الصورة الحالية">
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">الحالة</label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="is_active" value="1"
                                   <?php echo (!$product || $product['is_active']) ? 'checked' : ''; ?>
                                   style="width: 20px; height: 20px;">
                            <span>نشط (يظهر في الموقع)</span>
                        </label>
                    </div>
                    
                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i>
                            <?php echo $action === 'edit' ? 'حفظ التغييرات' : 'إضافة المنتج'; ?>
                        </button>
                        <a href="products.php" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>