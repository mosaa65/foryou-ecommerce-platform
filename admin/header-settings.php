<?php
/**
 * إدارة إعدادات الموقع - الفئات المعروضة في الرأس
 */
require_once '../app/config/database.php';
require_once 'includes/auth.php';
requireLogin();

$db = getDB();
$error = '';
$success = '';

// التأكد من وجود جدول الإعدادات
try {
    $db->exec("
        CREATE TABLE IF NOT EXISTS site_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT NULL,
            setting_type VARCHAR(50) DEFAULT 'text',
            description VARCHAR(255) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_setting_key (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    // إضافة الإعداد الافتراضي إذا لم يكن موجوداً
    $stmt = $db->prepare("SELECT COUNT(*) FROM site_settings WHERE setting_key = 'header_categories'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $db->exec("
            INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES
            ('header_categories', '[]', 'json', 'الفئات المختارة للعرض في رأس الموقع (3 فئات كحد أقصى)')
        ");
    }
} catch (PDOException $e) {
    error_log("Settings Table Error: " . $e->getMessage());
}

// معالجة POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedCategories = $_POST['header_categories'] ?? [];
    
    // التأكد من اختيار 3 فئات أو أقل
    if (count($selectedCategories) > 3) {
        $error = 'يمكنك اختيار 3 فئات كحد أقصى';
    } else {
        try {
            // تحويل القيم إلى أرقام صحيحة
            $selectedCategories = array_map('intval', $selectedCategories);
            $jsonValue = json_encode($selectedCategories);
            
            $stmt = $db->prepare("
                UPDATE site_settings 
                SET setting_value = ?, updated_at = NOW()
                WHERE setting_key = 'header_categories'
            ");
            $stmt->execute([$jsonValue]);
            
            // إذا لم يتم التحديث، قم بالإضافة
            if ($stmt->rowCount() === 0) {
                $stmt = $db->prepare("
                    INSERT INTO site_settings (setting_key, setting_value, setting_type, description)
                    VALUES ('header_categories', ?, 'json', 'الفئات المختارة للعرض في رأس الموقع')
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
                ");
                $stmt->execute([$jsonValue]);
            }
            
            $success = 'تم حفظ الإعدادات بنجاح';
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء حفظ الإعدادات';
            error_log("Settings Save Error: " . $e->getMessage());
        }
    }
}

// جلب الفئات المختارة الحالية
$currentSelection = [];
try {
    $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'header_categories'");
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result && $result['setting_value']) {
        $currentSelection = json_decode($result['setting_value'], true) ?: [];
    }
} catch (PDOException $e) {
    error_log("Settings Fetch Error: " . $e->getMessage());
}

// جلب جميع الفئات النشطة
$categories = [];
try {
    $stmt = $db->query("
        SELECT id, name, icon, slug,
               (SELECT COUNT(*) FROM subcategories WHERE category_id = categories.id) as subcategories_count
        FROM categories 
        WHERE is_active = 1 
        ORDER BY sort_order, id
    ");
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Categories Fetch Error: " . $e->getMessage());
}

$pageTitle = 'إعدادات رأس الموقع';
require_once 'includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fa-solid fa-heading"></i>
            الفئات المعروضة في رأس الموقع
        </h2>
    </div>
    <div class="card-body">
        <div class="alert alert-info" style="background: var(--info-soft); color: var(--info); border: 1px solid rgba(59, 130, 246, 0.3);">
            <i class="fa-solid fa-circle-info"></i>
            اختر 3 فئات كحد أقصى لعرضها في رأس الموقع. الفئات غير المختارة ستكون متاحة عبر صفحة المعرض.
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="headerCategoriesForm">
            <div class="categories-selection-grid">
                <?php foreach ($categories as $cat): ?>
                    <label class="category-selection-card <?php echo in_array($cat['id'], $currentSelection) ? 'selected' : ''; ?>">
                        <input type="checkbox" 
                               name="header_categories[]" 
                               value="<?php echo $cat['id']; ?>"
                               <?php echo in_array($cat['id'], $currentSelection) ? 'checked' : ''; ?>
                               class="category-checkbox">
                        <div class="category-card-content">
                            <div class="category-icon">
                                <i class="<?php echo htmlspecialchars($cat['icon']); ?>"></i>
                            </div>
                            <div class="category-info">
                                <h3><?php echo htmlspecialchars($cat['name']); ?></h3>
                                <span class="category-count"><?php echo $cat['subcategories_count']; ?> قسم فرعي</span>
                            </div>
                            <div class="category-check">
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
            
            <div class="selection-counter">
                <span id="selectedCount"><?php echo count($currentSelection); ?></span> / 3 فئات مختارة
            </div>
            
            <div class="form-actions" style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" id="saveBtn">
                    <i class="fa-solid fa-check"></i>
                    حفظ الإعدادات
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.categories-selection-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    margin: 24px 0;
}

.category-selection-card {
    display: block;
    background: var(--bg-hover);
    border: 2px solid var(--border);
    border-radius: var(--radius);
    cursor: pointer;
    transition: all var(--transition);
    position: relative;
    overflow: hidden;
}

.category-selection-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
}

.category-selection-card.selected {
    border-color: var(--primary);
    background: var(--primary-soft);
}

.category-selection-card.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.category-selection-card input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.category-card-content {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
}

.category-icon {
    width: 56px;
    height: 56px;
    background: var(--primary-soft);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--primary);
    flex-shrink: 0;
}

.category-selection-card.selected .category-icon {
    background: var(--primary);
    color: white;
}

.category-info {
    flex: 1;
}

.category-info h3 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 4px;
}

.category-count {
    font-size: 13px;
    color: var(--text-muted);
}

.category-check {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition);
}

.category-check i {
    opacity: 0;
    color: white;
    font-size: 16px;
    transition: all var(--transition);
}

.category-selection-card.selected .category-check {
    background: var(--success);
    border-color: var(--success);
}

.category-selection-card.selected .category-check i {
    opacity: 1;
}

.selection-counter {
    text-align: center;
    padding: 16px;
    background: var(--bg-hover);
    border-radius: var(--radius);
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
}

.selection-counter #selectedCount {
    color: var(--primary);
    font-size: 24px;
}

@media (max-width: 768px) {
    .categories-selection-grid {
        grid-template-columns: 1fr;
    }
    
    .category-card-content {
        padding: 16px;
    }
    
    .category-icon {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const MAX_SELECTIONS = 3;
    const checkboxes = document.querySelectorAll('.category-checkbox');
    const counter = document.getElementById('selectedCount');
    const saveBtn = document.getElementById('saveBtn');
    
    function updateSelection() {
        const selectedCount = document.querySelectorAll('.category-checkbox:checked').length;
        counter.textContent = selectedCount;
        
        // تطبيق التأثيرات على البطاقات
        checkboxes.forEach(checkbox => {
            const card = checkbox.closest('.category-selection-card');
            if (checkbox.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
            
            // تعطيل الخيارات غير المختارة إذا وصلنا للحد الأقصى
            if (selectedCount >= MAX_SELECTIONS && !checkbox.checked) {
                card.classList.add('disabled');
                checkbox.disabled = true;
            } else {
                card.classList.remove('disabled');
                checkbox.disabled = false;
            }
        });
        
        // تغيير لون العداد
        if (selectedCount > MAX_SELECTIONS) {
            counter.style.color = 'var(--danger)';
        } else if (selectedCount === MAX_SELECTIONS) {
            counter.style.color = 'var(--success)';
        } else {
            counter.style.color = 'var(--primary)';
        }
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });
    
    // تحديث أولي
    updateSelection();
});
</script>

<?php require_once 'includes/footer.php'; ?>