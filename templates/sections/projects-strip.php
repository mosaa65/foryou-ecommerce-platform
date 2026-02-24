<?php
/**
 * قسم شريط المشاريع - يجلب البيانات من قاعدة البيانات
 */
require_once __DIR__ . '/../../app/config/database.php';

// جلب منتجات مميزة لعرضها في الشريط
function getStripProducts($limit = 8) {
    $db = getDB();
    if (!$db) {
        return [];
    }
    
    try {
        $stmt = $db->prepare("
            SELECT p.id, p.name, p.description, p.image_path, 
                   s.name as subcategory_name, s.slug as subcategory_slug
            FROM products p
            JOIN subcategories s ON p.subcategory_id = s.id
            WHERE p.is_active = 1 
              AND s.is_active = 1
              AND p.image_path IS NOT NULL 
              AND p.image_path != ''
            ORDER BY p.views_count DESC, p.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("getStripProducts Error: " . $e->getMessage());
    }
    
    return [];
}

$stripProducts = getStripProducts(8);

// إذا لم توجد منتجات، لا نعرض القسم
if (empty($stripProducts)) {
    return;
}
?>

<section class="projects-strip reveal" id="projects-strip">
    <div class="section-wrapper">
        <div class="projects-strip-header">
            <span class="eyebrow">هدايا مختارة بعناية</span>
            <h2 class="service-quick-title">بوكسات فاخرة من من أجلك</h2>
            <p class="section-subtitle">
                مجموعة من أجمل البوكسات والهدايا الجاهزة والمخصصة، صُممت بعناية
                لتناسب جميع المناسبات وتلائم كل الأذواق، مع تغليف فاخر ولمسات أنيقة.
            </p>
        </div>

        <div class="projects-grid-strip">

            <?php foreach ($stripProducts as $product): ?>
                <?php
                $imagePath = htmlspecialchars($product['image_path']);
                $title = htmlspecialchars($product['name']);
                $desc = htmlspecialchars($product['description'] ?? '');
                $categoryName = htmlspecialchars($product['subcategory_name']);
                
                // تحديد التاجات بناءً على اسم القسم
                $tagMain = $categoryName;
                $tagSoft = '';
                
                $catLower = mb_strtolower($categoryName, 'UTF-8');
                if (strpos($catLower, 'عطور') !== false) {
                    $tagSoft = 'عطور • اكسسوارات';
                } elseif (strpos($catLower, 'ورد') !== false) {
                    $tagSoft = 'شموع • ورد';
                } elseif (strpos($catLower, 'مواليد') !== false) {
                    $tagSoft = 'مواليد • مباركة';
                } elseif (strpos($catLower, 'تخرج') !== false) {
                    $tagSoft = 'تخرج • احتفال';
                } elseif (strpos($catLower, 'ميلاد') !== false) {
                    $tagSoft = 'عيد ميلاد';
                } elseif (strpos($catLower, 'شوكولاتة') !== false) {
                    $tagSoft = 'شوكولاتة فاخرة';
                } else {
                    $tagSoft = 'هدية مميزة';
                }
                ?>

                <article class="project-card-strip">
                    <div class="project-img-strip"
                         style="background-image:url('<?php echo $imagePath; ?>');">
                    </div>

                    <div class="project-label-strip">
                        <span class="tag-strip"><?php echo $tagMain; ?></span>
                        <span class="tag-soft-strip"><?php echo $tagSoft; ?></span>
                    </div>

                    <h3 class="project-title-strip">
                        <?php echo $title; ?>
                    </h3>

                    <p class="project-text-strip">
                        <?php echo mb_substr($desc, 0, 100, 'UTF-8'); ?><?php echo mb_strlen($desc, 'UTF-8') > 100 ? '...' : ''; ?>
                    </p>
                </article>

            <?php endforeach; ?>

        </div>
    </div>
</section>