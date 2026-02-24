<?php
/**
 * قسم خدمات التخصيص - يجلب البيانات من قاعدة البيانات
 */
require_once __DIR__ . '/../../app/config/database.php';

// أقسام خدمات التخصيص المطلوب عرضها
$customSlugs = ['name-print', 'ring-engraving', 'wood-engraving', 'keychains'];

// وصف وملاحظة مخصصة لكل خدمة
$customDetails = [
    'name-print' => ['tagline' => 'طباعة أسماء ورسائل خاصة على الهدايا والبوكسات.', 'note' => 'مثالية للهدايا الشخصية.'],
    'ring-engraving' => ['tagline' => 'نقش أسماء وتواريخ خاصة داخل الخواتم.', 'note' => 'مثالية للخطوبة والزواج.'],
    'wood-engraving' => ['tagline' => 'نحت عبارات وتصاميم على قطع خشبية مخصّصة.', 'note' => 'تضيف لمسة فنية للهدايا.'],
    'keychains' => ['tagline' => 'ميداليات بأسماء وأشكال تناسب المناسبة.', 'note' => 'هدية عملية تُستخدم يومياً.'],
];

// جلب البيانات من قاعدة البيانات
$customServices = getSubcategoriesBySlugs($customSlugs);

// إذا لم توجد بيانات، استخدم بيانات ثابتة كـ fallback
if (empty($customServices)) {
    $customServices = [
        ['slug' => 'name-print', 'name' => 'طباعة أسماء', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'ring-engraving', 'name' => 'نقش خواتم', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'wood-engraving', 'name' => 'نحت خشب', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'keychains', 'name' => 'ميداليات مخصّصة', 'thumbnail' => 'public/favicon.png'],
    ];
}
?>

<section class="custom-section">
    <div class="custom-inner">
        <header class="custom-header">
            <h2 class="custom-title">خدمات التخصيص</h2>
            <p class="custom-subtitle">
                لخدمة الهدايا بشكل أرقى، نقدّم طباعة أسماء، نقش خواتم، نحت خشب وميداليات مخصّصة  
                بتفاصيل دقيقة تجعل الهديّة تحمل اسم الشخص ولمسته الخاصة.
            </p>
        </header>

        <div class="custom-grid">
            <?php $i = 0; ?>
            <?php foreach ($customServices as $srv): ?>
                <?php 
                $details = $customDetails[$srv['slug']] ?? ['tagline' => $srv['description'] ?? '', 'note' => ''];
                $imgSrc = $srv['thumbnail'] ?? 'public/favicon.png';
                ?>

                <a href="projects.php?category=<?php echo htmlspecialchars($srv['slug']); ?>"
                   class="custom-card"
                   data-index="<?php echo $i++; ?>">

                    <div class="custom-card-pin"></div>

                    <div class="custom-card-inner">
                        <div class="custom-card-left">
                            <div class="custom-card-label">
                                <span class="custom-card-label-main">
                                    <?php echo htmlspecialchars($srv['name']); ?>
                                </span>
                                <span class="custom-card-label-note">
                                    <?php echo htmlspecialchars($details['note']); ?>
                                </span>
                            </div>

                            <p class="custom-card-text">
                                <?php echo htmlspecialchars($details['tagline']); ?>
                            </p>

                            <div class="custom-card-footer">
                                <span class="custom-card-cta">
                                    اطلب خدمة التخصيص
                                </span>
                                <span class="custom-card-dash"></span>
                            </div>
                        </div>

                        <div class="custom-card-right">
                            <div class="custom-card-image-frame">
                                <img src="<?php echo htmlspecialchars($imgSrc); ?>"
                                     alt="<?php echo htmlspecialchars($srv['name']); ?>">
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>