<?php
/**
 * قسم عرض الهدايا المميزة - يجلب البيانات من قاعدة البيانات
 */
require_once __DIR__ . '/../../app/config/database.php';

// جلب المنتجات المميزة من قاعدة البيانات مجمعة حسب القسم
function getShowcaseProducts($limit = 8) {
    $db = getDB();
    if (!$db) {
        return [];
    }
    
    try {
        $stmt = $db->prepare("
            SELECT p.id, p.name, p.description, p.image_path, 
                   s.name as subcategory_name, s.slug as subcategory_slug,
                   c.name as category_name
            FROM products p
            JOIN subcategories s ON p.subcategory_id = s.id
            JOIN categories c ON s.category_id = c.id
            WHERE p.is_active = 1 
              AND s.is_active = 1
              AND p.image_path IS NOT NULL 
              AND p.image_path != ''
            ORDER BY RAND()
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("getShowcaseProducts Error: " . $e->getMessage());
    }
    
    return [];
}

$showcaseProducts = getShowcaseProducts(8);

// إذا لم توجد منتجات، لا نعرض القسم
if (empty($showcaseProducts)) {
    return; // لا نعرض القسم إذا لم توجد منتجات
}

// تجميع المنتجات مع صورها
$villaShowcase = [];
foreach ($showcaseProducts as $product) {
    $villaShowcase[] = [
        'title' => $product['name'],
        'category' => $product['subcategory_name'],
        'category_slug' => $product['subcategory_slug'],
        'description' => $product['description'] ?? '',
        'images' => [$product['image_path']]
    ];
}
?>

<section class="reveal villa-showcase">
    <div class="section-wrapper">

        <h2 class="section-title">مجموعة من أجمل الهدايا</h2>

        <p class="section-subtitle">
            بوكسات وهدايا جاهزة ومخصصة بلمسات فاخرة، مع تنسيق احترافي يليق بذوقك.
        </p>

        <div class="villa-grid">
            <?php foreach ($villaShowcase as $item): ?>
                <?php if (!empty($item['images'])): ?>

                    <?php
                        // تحديد التاج حسب الفئة
                        $tag = 'تنسيق متعدد — صور متنوعة';
                        $category = $item['category'];
                        
                        if (stripos($category, 'رجال') !== false) {
                            $tag = 'تنسيق فاخر — لقطات متعددة';
                        } elseif (stripos($category, 'نسائ') !== false) {
                            $tag = 'تغليف وردي — لمسات ذهبية';
                        } elseif (stripos($category, 'مواليد') !== false) {
                            $tag = 'هدايا ناعمة — مناسبات مباركة';
                        } elseif (stripos($category, 'تخرج') !== false || stripos($category, 'ميلاد') !== false) {
                            $tag = 'احتفال — تخرج / ميلاد';
                        } elseif (stripos($category, 'منزل') !== false) {
                            $tag = 'ديكور • شموع — هدية راقية';
                        } elseif (stripos($category, 'عطور') !== false || stripos($category, 'فاخر') !== false) {
                            $tag = 'فخامة عالية — إصدار مميز';
                        }
                    ?>

                    <div class="villa-card"
                        data-images='<?php echo json_encode($item["images"], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>'>

                        <div class="villa-label">
                            <?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>

                        <div class="villa-img-wrapper">
                            <button class="villa-img-arrow villa-img-prev" type="button">‹</button>
                            <div class="villa-img" style="background-image: url('<?php echo htmlspecialchars($item['images'][0]); ?>');"></div>
                            <button class="villa-img-arrow villa-img-next" type="button">›</button>
                        </div>

                        <div class="villa-tag">
                            <?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>
                        </div>

                        <div class="villa-caption">
                            <?php echo htmlspecialchars(mb_substr($item['description'], 0, 120, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>
                            <?php echo mb_strlen($item['description'], 'UTF-8') > 120 ? '...' : ''; ?>
                        </div>

                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="villa-nav">
            <button id="prevCards" class="villa-nav-btn" type="button">‹</button>
            <button id="nextCards" class="villa-nav-btn" type="button">›</button>
        </div>

    </div>
</section>

<!-- اللايت بوكس -->
<div class="villa-lightbox" id="villaLightbox">
    <div class="villa-lightbox-overlay"></div>

    <button class="villa-lightbox-close" type="button">×</button>
    <button class="villa-lightbox-nav villa-lightbox-prev" type="button">‹</button>
    <button class="villa-lightbox-nav villa-lightbox-next" type="button">›</button>

    <div class="villa-lightbox-content">
        <img id="villaLightboxImage" src="" alt="">
        <div class="villa-lightbox-caption">
            <span id="villaLightboxTitle"></span>
            <span id="villaLightboxCounter"></span>
        </div>
    </div>
</div>