<?php
/**
 * صفحة تفاصيل المنتج
 * متجر "من أجلك" للهدايا
 */

// الاتصال بقاعدة البيانات
require_once BASE_PATH . '/app/config/database.php';
$db = getDB();

// قراءة slug من الرابط
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$currentProduct = null;
$categoryLabel = '';
$subcategoryLabel = '';

if ($db && $slug !== '') {
    try {
        // جلب المنتج مع بيانات الفئة والقسم
        $stmt = $db->prepare("
            SELECT 
                p.id, p.name, p.slug, p.description, p.price, p.image_path,
                s.name as subcategory_name, s.slug as subcategory_slug,
                c.name as category_name, c.slug as category_slug
            FROM products p
            JOIN subcategories s ON p.subcategory_id = s.id
            JOIN categories c ON s.category_id = c.id
            WHERE p.slug = ? AND p.is_active = 1
        ");
        $stmt->execute([$slug]);
        $product = $stmt->fetch();
        
        if ($product) {
            $currentProduct = [
                'id'              => $product['id'],
                'slug'            => $product['slug'],
                'title'           => $product['name'],
                'description'     => $product['description'],
                'price'           => $product['price'],
                'category_slug'   => $product['category_slug'],
                'category_name'   => $product['category_name'],
                'subcategory_slug'=> $product['subcategory_slug'],
                'subcategory_name'=> $product['subcategory_name'],
                'image_path'      => $product['image_path'],
                'images'          => $product['image_path'] ? [$product['image_path']] : []
            ];
            $categoryLabel = $product['category_name'];
            $subcategoryLabel = $product['subcategory_name'];
        }
    } catch (PDOException $e) {
        error_log("Gift Page Error: " . $e->getMessage());
    }
}

// تحضير العناوين
if ($currentProduct) {
    $pageTitle = "تفاصيل هدية - " . $currentProduct['title'] . " | من أجلك للهدايا";
} else {
    $pageTitle = "تفاصيل هدية - من أجلك للهدايا";
}

$pageExtraCss = '<link rel="stylesheet" href="public/css/gift.css">';
$pageExtraJs  = '<script src="public/js/gift.js" defer></script>';

require_once BASE_PATH . '/templates/layouts/header.php';
?>

<?php if (!$currentProduct): ?>

    <!-- حالة عدم وجود الهدية -->
    <section class="reveal gift-not-found">
        <div class="section-wrapper">
            <div class="gift-not-found-inner">
                <h1 class="gift-not-found-title">عذراً، هذه الهدية غير متوفرة</h1>
                <p class="gift-not-found-text">
                    قد تكون الهدية غير موجودة أو تم تحديث الرابط. يمكنك العودة إلى كتالوج الهدايا
                    واستكشاف خيارات أخرى تناسب ذوقك.
                </p>
                <a href="projects.php" class="gift-back-btn">العودة إلى كتالوج الهدايا</a>
            </div>
        </div>
    </section>

<?php else: ?>

    <?php
        $images      = $currentProduct['images'];
        $cover       = $images[0] ?? '';
        $whatsText   = rawurlencode("مرحباً، أرغب في طلب هذه الهدية:\n" . $currentProduct['title']);
    ?>

    <!-- Hero -->
    <section class="reveal gift-hero">
        <div class="section-wrapper">
            <div class="gift-hero-inner">
                <div class="gift-hero-breadcrumb">
                    <a href="projects.php">كتالوج الهدايا</a>
                    <span>/</span>
                    <span><?php echo htmlspecialchars($categoryLabel); ?></span>
                    <span>/</span>
                    <span><?php echo htmlspecialchars($subcategoryLabel); ?></span>
                </div>

                <span class="gift-hero-label"><?php echo htmlspecialchars($subcategoryLabel); ?></span>

                <h1 class="gift-hero-title">
                    <?php echo htmlspecialchars($currentProduct['title']); ?>
                </h1>

                <p class="gift-hero-subtitle">
                    <?php echo htmlspecialchars($currentProduct['description'] ?: 'هدية مميزة من متجر من أجلك للهدايا'); ?>
                </p>

                <div class="gift-hero-meta">
                    <span>
                        التصنيف:
                        <strong><?php echo htmlspecialchars($categoryLabel); ?></strong>
                    </span>
                    <?php if ($currentProduct['price']): ?>
                        <span>
                            السعر:
                            <strong><?php echo number_format($currentProduct['price'], 2); ?> ر.س</strong>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="gift-hero-actions">
                    <a href="https://wa.me/967772217218?text=<?php echo $whatsText; ?>"
                       class="gift-hero-whatsapp"
                       target="_blank"
                       rel="noopener noreferrer">
                        اطلب هذه الهدية عبر واتساب
                    </a>
                    <a href="projects.php" class="gift-hero-back">
                        العودة إلى كتالوج الهدايا
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- معرض الصور -->
    <section class="reveal gift-gallery">
        <div class="section-wrapper">
            <div class="gift-gallery-layout">
                <!-- الصورة الرئيسية -->
                <div class="gift-gallery-main">
                    <?php if ($cover): ?>
                        <img src="<?php echo htmlspecialchars($cover); ?>"
                             alt="<?php echo htmlspecialchars($currentProduct['title']); ?>"
                             class="gift-main-image"
                             id="giftMainImage">
                    <?php else: ?>
                        <div class="gift-main-placeholder">
                            لا توجد صورة رئيسية
                        </div>
                    <?php endif; ?>
                </div>

                <!-- الصور المصغرة -->
                <?php if (!empty($images) && count($images) > 1): ?>
                    <div class="gift-thumbs">
                        <?php foreach ($images as $index => $img): ?>
                            <button
                                type="button"
                                class="gift-thumb<?php echo $index === 0 ? ' active' : ''; ?>"
                                data-full-src="<?php echo htmlspecialchars($img); ?>">
                                <img src="<?php echo htmlspecialchars($img); ?>"
                                     alt="<?php echo htmlspecialchars($currentProduct['title']); ?> - صورة <?php echo $index + 1; ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- تفاصيل إضافية -->
    <section class="reveal gift-details">
        <div class="section-wrapper">
            <h2 class="gift-details-title">تفاصيل الهدية</h2>
            <p class="gift-details-text">
                يمكن تصميم هذه الهدية بما يناسب ذوقك والمناسبة:
            </p>
            <ul class="gift-details-list">
                <li>إمكانية إضافة بطاقة تهنئة بالاسم والعبارة التي ترغب بها.</li>
                <li>تعديل ألوان التغليف والتنسيق حسب المناسبة (عيد ميلاد، تخرج، خطوبة، زواج، مواليد وغيرها).</li>
                <li>إضافة عناصر إضافية داخل البوكس مثل شوكولاتة، عطور، اكسسوارات، دباديب وغير ذلك.</li>
                <li>تنسيق خاص للطلبات الكبيرة أو الهدايا الجماعية.</li>
            </ul>

            <div class="gift-details-cta">
                <a href="https://wa.me/967772217218?text=<?php echo $whatsText; ?>"
                   class="gift-hero-whatsapp"
                   target="_blank"
                   rel="noopener noreferrer">
                    تواصل معنا لتخصيص هذه الهدية
                </a>
            </div>
        </div>
    </section>

    <!-- هدايا مشابهة -->
    <?php
        $similar = [];
        if ($db) {
            try {
                $stmt = $db->prepare("
                    SELECT p.id, p.name, p.slug, p.image_path, s.name as subcategory_name
                    FROM products p
                    JOIN subcategories s ON p.subcategory_id = s.id
                    JOIN categories c ON s.category_id = c.id
                    WHERE c.slug = ? AND p.slug != ? AND p.is_active = 1
                    ORDER BY RAND()
                    LIMIT 3
                ");
                $stmt->execute([$currentProduct['category_slug'], $currentProduct['slug']]);
                $similar = $stmt->fetchAll();
            } catch (PDOException $e) {
                error_log("Similar Products Error: " . $e->getMessage());
            }
        }
    ?>

    <?php if (!empty($similar)): ?>
        <section class="reveal gift-related">
            <div class="section-wrapper">
                <h2 class="gift-related-title">هدايا مشابهة قد تعجبك</h2>
                <div class="gift-related-grid">
                    <?php foreach ($similar as $item): 
                        $simCover = $item['image_path'] ?: 'public/images/placeholder.png';
                    ?>
                        <article class="gift-related-card">
                            <div class="gift-related-img-wrapper">
                                <img src="<?php echo htmlspecialchars($simCover); ?>"
                                     alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="gift-related-content">
                                <span class="gift-related-cat">
                                    <?php echo htmlspecialchars($item['subcategory_name']); ?>
                                </span>
                                <h3 class="gift-related-name">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </h3>
                                <a href="gift?slug=<?php echo urlencode($item['slug']); ?>"
                                   class="gift-related-link">
                                    عرض تفاصيل الهدية
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

<?php endif; ?>

<?php require_once BASE_PATH . '/templates/layouts/footer.php'; ?>