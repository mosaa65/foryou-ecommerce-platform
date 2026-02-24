<?php
/**
 * قسم الهدايا الفاخرة - يجلب البيانات من قاعدة البيانات
 */
require_once __DIR__ . '/../../app/config/database.php';

// أقسام الهدايا الفاخرة المطلوب عرضها
$premiumSlugs = ['perfumes', 'watches', 'phones', 'makeup', 'accessories'];

// وصف وشارة مخصصة لكل فئة
$premiumDetails = [
    'perfumes' => ['tagline' => 'تشكيلة عطور مختارة بعناية من روائح شرقية وعالمية.', 'badge' => 'لمسة فخامة'],
    'watches' => ['tagline' => 'ساعات أنيقة تناسب الإهداء الرسمي والخاص.', 'badge' => 'تفاصيل الوقت'],
    'phones' => ['tagline' => 'بوكسات مميّزة مع جوالات وهدايا مكمّلة.', 'badge' => 'اختيار استثنائي'],
    'makeup' => ['tagline' => 'هدايا مكياج بعلامات مفضّلة ولمسات أنثوية أنيقة.', 'badge' => 'جمال وإطلالة'],
    'accessories' => ['tagline' => 'اكسسوارات فاخرة تضيف على الهديّة لمسة ذوق.', 'badge' => 'تفاصيل أنيقة'],
];

// جلب البيانات من قاعدة البيانات
$premiumCategories = getSubcategoriesBySlugs($premiumSlugs);

// إذا لم توجد بيانات، استخدم بيانات ثابتة كـ fallback
if (empty($premiumCategories)) {
    $premiumCategories = [
        ['slug' => 'perfumes', 'name' => 'عطور فاخرة', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'watches', 'name' => 'ساعات', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'phones', 'name' => 'هدايا مع جوالات', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'makeup', 'name' => 'مكياج', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'accessories', 'name' => 'اكسسوارات', 'thumbnail' => 'public/favicon.png'],
    ];
}
?>

<section class="premium-section">
    <div class="premium-inner">
        <header class="premium-header">
            <h2 class="premium-title">هدايا فاخرة</h2>
            <p class="premium-subtitle">
                اختيارات فاخرة من العطور، الساعات، الجوالات، المكياج والاكسسوارات،  
                لهدايا تقدّم بإحساس راقٍ وتليق بالمناسبات الخاصة.
            </p>
        </header>

        <div class="premium-grid">
            <?php $i = 0; ?>
            <?php foreach ($premiumCategories as $cat): ?>
                <?php 
                $details = $premiumDetails[$cat['slug']] ?? ['tagline' => $cat['description'] ?? '', 'badge' => 'مميز'];
                $imgSrc = $cat['thumbnail'] ?? 'public/favicon.png';
                ?>

                <a href="projects.php?category=<?php echo htmlspecialchars($cat['slug']); ?>"
                   class="premium-card"
                   data-index="<?php echo $i++; ?>">

                    <div class="premium-card-glow"></div>

                    <div class="premium-card-inner">
                        <div class="premium-card-left">
                            <div class="premium-card-badge">
                                <span class="premium-card-badge-icon">★</span>
                                <span class="premium-card-badge-text">
                                    <?php echo htmlspecialchars($details['badge']); ?>
                                </span>
                            </div>

                            <h3 class="premium-card-title">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </h3>

                            <p class="premium-card-text">
                                <?php echo htmlspecialchars($details['tagline']); ?>
                            </p>

                            <div class="premium-card-footer">
                                <span class="premium-card-cta">
                                    استعرض الهدايا الفاخرة
                                </span>
                                <span class="premium-card-line"></span>
                            </div>
                        </div>

                        <div class="premium-card-right">
                            <div class="premium-card-image-frame">
                                <img src="<?php echo htmlspecialchars($imgSrc); ?>"
                                     alt="<?php echo htmlspecialchars($cat['name']); ?>">
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>