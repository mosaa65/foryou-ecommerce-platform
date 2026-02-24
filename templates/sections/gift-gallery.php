<?php
/**
 * قسم معرض الهدايا - يجلب البيانات من قاعدة البيانات
 */
require_once __DIR__ . '/../../app/config/database.php';

// جلب آخر 6 منتجات مع صورها من قاعدة البيانات
function getGalleryProducts($limit = 6) {
    $db = getDB();
    if (!$db) {
        return [];
    }
    
    try {
        $stmt = $db->prepare("
            SELECT p.id, p.name, p.description, p.image_path, s.name as subcategory_name
            FROM products p
            JOIN subcategories s ON p.subcategory_id = s.id
            WHERE p.is_active = 1 
              AND s.is_active = 1
              AND p.image_path IS NOT NULL 
              AND p.image_path != ''
            ORDER BY p.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("getGalleryProducts Error: " . $e->getMessage());
    }
    
    return [];
}

$galleryProducts = getGalleryProducts(6);

// إذا لم توجد منتجات، استخدم بيانات ثابتة كـ fallback
if (empty($galleryProducts)) {
    $galleryProducts = [
        [
            'name' => 'بوكس هدايا رجالي فاخر',
            'description' => 'تنسيق فاخر يجمع بين العود، العطر الرجالي الفخم، والاكسسوارات الراقية، مغلف بطريقة أنيقة تجعل الهدية متكاملة وجاهزة للتقديم.',
            'image_path' => 'public/favicon.png',
            'subcategory_name' => 'هدايا رجالية'
        ],
        [
            'name' => 'بوكس نسائي بتنسيق وردي فاخر',
            'description' => 'هدية مميزة تجمع بين العطر النسائي الفاخر، الشموع المعطرة، والورد المُجفف. تنسيق ناعم يناسب أعياد الميلاد.',
            'image_path' => 'public/favicon.png',
            'subcategory_name' => 'هدايا نسائية'
        ],
        [
            'name' => 'بوكس مواليد بتغليف راقٍ',
            'description' => 'بوكس مخصص لمواليد البنات أو الأولاد، يحتوي على قطعة قماشية ناعمة، عطر أطفال، وبطاقة تهنئة.',
            'image_path' => 'public/favicon.png',
            'subcategory_name' => 'هدايا مواليد'
        ],
    ];
}
?>

<section class="ba-section reveal" id="gift-gallery">
    <div class="section-wrapper">
        <header class="ba-header">
            <span class="ba-eyebrow">هدايا مختارة بعناية</span>
            <h2 class="ba-title">بوكسات وهدايا فاخرة من من أجلك</h2>
            <p class="ba-subtitle">
                مجموعة من الهدايا المميزة المصممة بعناية، تجمع بين اللمسة الفاخرة
                وتناسق الألوان والتغليف الراقي. يمكنك استعراض التفاصيل، تكبير الصور،
                والاختيار بين بوكسات جاهزة أو تصميم هدية خاصة حسب المناسبة.
            </p>
        </header>

        <div class="ba-grid">

            <?php foreach ($galleryProducts as $product): ?>
                <?php 
                $imgSrc = !empty($product['image_path']) ? htmlspecialchars($product['image_path']) : 'public/favicon.png';
                $title = htmlspecialchars($product['name']);
                $desc = htmlspecialchars($product['description'] ?? '');
                $category = htmlspecialchars($product['subcategory_name'] ?? 'هدية مميزة');
                ?>

                <article class="ba-card"
                         data-ba-card
                         data-title="<?php echo $title; ?>"
                         data-text="<?php echo $desc; ?>">

                    <div class="ba-image-wrap" data-ba-open>
                        <img
                            src="<?php echo $imgSrc; ?>"
                            alt="<?php echo $title; ?>"
                            loading="lazy"
                            data-ba-img
                            data-alt-base="<?php echo $title; ?>"
                        >
                        <span class="ba-category-badge"><?php echo $category; ?></span>
                    </div>

                    <div class="ba-content">
                        <h3 class="ba-project-title"><?php echo $title; ?></h3>
                        <p class="ba-project-text">
                            <?php echo mb_substr($desc, 0, 100, 'UTF-8'); ?><?php echo mb_strlen($desc, 'UTF-8') > 100 ? '...' : ''; ?>
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>

        </div>

        <p class="ba-note">
            جميع الهدايا قابلة للتخصيص بالكامل: اختيار الألوان، نوع العطر، الإضافات،
            وطريقة التغليف، مع إمكانية كتابة بطاقة إهداء خاصة باسمك.
        </p>

        <!-- لايت بوكس مبسّط لعرض الصور -->
        <div class="ba-lightbox" data-ba-lightbox hidden>
            <div class="ba-lightbox-backdrop" data-ba-close></div>
            <div class="ba-lightbox-inner">
                <button class="ba-lightbox-close" type="button" aria-label="إغلاق" data-ba-close>&times;</button>

                <div class="ba-lightbox-media">
                    <img src="" alt="" loading="lazy" data-ba-lb-img>
                </div>

                <p class="ba-lightbox-caption" data-ba-lb-caption></p>
            </div>
        </div>
    </div>
</section>