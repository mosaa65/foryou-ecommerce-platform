<?php
/**
 * قسم المناسبات - يجلب البيانات من قاعدة البيانات
 */
require_once __DIR__ . '/../../app/config/database.php';

// أقسام المناسبات المطلوب عرضها
$occasionSlugs = ['birthday', 'graduation', 'engagement', 'wedding', 'newborn', 'mothers-day'];

// وصف مخصص لكل مناسبة
$occasionDescriptions = [
    'birthday' => 'بوكسات، ورد، شوكولاتة وبطاقة إهداء خاصة.',
    'graduation' => 'هدايا تخلّد لحظة النجاح بأناقة.',
    'engagement' => 'تنسيقات رقيقة تناسب جو الخطوبة.',
    'wedding' => 'بوكسات مميّزة وقطع فاخرة للعرسان.',
    'newborn' => 'هدايا ناعمة بلمسات طفولية لطيفة.',
    'mothers-day' => 'اختيارات تليق بقلب الأم وذوقها.',
];

// جلب البيانات من قاعدة البيانات
$occasions = getSubcategoriesBySlugs($occasionSlugs);

// إذا لم توجد بيانات، استخدم بيانات ثابتة كـ fallback
if (empty($occasions)) {
    $occasions = [
        ['slug' => 'birthday', 'name' => 'هدايا عيد ميلاد', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'graduation', 'name' => 'هدايا تخرج', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'engagement', 'name' => 'هدايا خطوبة', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'wedding', 'name' => 'هدايا زواج', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'newborn', 'name' => 'هدايا مواليد', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'mothers-day', 'name' => 'هدايا عيد الأم', 'thumbnail' => 'public/favicon.png'],
    ];
}
?>

<section class="occasion-section">
    <div class="section-wrapper">
        <h2 class="section-title">اختَر المناسبة</h2>
        <p class="section-subtitle">
            اختر نوع المناسبة، واستعرض تشكيلات جاهزة من البوكسات، الورود، الشوكولاتة والهدايا الفاخرة.
        </p>

        <div class="cards-grid occasion-grid">
            <?php $i = 0; ?>
            <?php foreach ($occasions as $occ): ?>
                <?php 
                $description = $occasionDescriptions[$occ['slug']] ?? ($occ['description'] ?? '');
                $thumbSrc = $occ['thumbnail'] ?? 'public/favicon.png';
                ?>

                <a href="projects.php?category=<?php echo htmlspecialchars($occ['slug']); ?>"
                   class="card card-occasion reveal"
                   data-index="<?php echo $i++; ?>">

                    <div class="occasion-card-image">
                        <img src="<?php echo htmlspecialchars($thumbSrc); ?>"
                             alt="<?php echo htmlspecialchars($occ['name']); ?>">
                    </div>

                    <div class="occasion-card-content">
                        <span class="pill occasion-pill">
                            مناسبة: <?php echo htmlspecialchars($occ['name']); ?>
                        </span>

                        <h3 class="occasion-card-title">
                            <?php echo htmlspecialchars($occ['name']); ?>
                        </h3>

                        <p class="occasion-card-description">
                            <?php echo htmlspecialchars($description); ?>
                        </p>

                        <span class="occasion-card-cta">
                            تصفّح هدايا هذه المناسبة
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>