<?php
/**
 * قسم التشكيلات الجاهزة - يجلب البيانات من قاعدة البيانات
 */
require_once __DIR__ . '/../../app/config/database.php';

// أقسام التشكيلات المطلوب عرضها
$collectionSlugs = ['natural-flowers', 'artificial-flowers', 'chocolate', 'boxes', 'full-packages', 'teddy'];

// وصف مخصص لكل تشكيلة
$collectionDescriptions = [
    'natural-flowers' => 'تنسيقات ورد طبيعي فخمة جاهزة للإهداء.',
    'artificial-flowers' => 'تشكيلات ورد صناعي تبقى ذكرى لفترة أطول.',
    'chocolate' => 'بوكيهات شوكولاتة فاخرة بتقديم جذاب.',
    'boxes' => 'بوكسات متنوعة بأحجام وأشكال مختلفة.',
    'full-packages' => 'تشكيلات جاهزة تضم أكثر من عنصر في بوكس واحد.',
    'teddy' => 'دباديب ودمى لطيفة تضيف لمسة حب للهديّة.',
];

// جلب البيانات من قاعدة البيانات
$collections = getSubcategoriesBySlugs($collectionSlugs);

// إذا لم توجد بيانات، استخدم بيانات ثابتة كـ fallback
if (empty($collections)) {
    $collections = [
        ['slug' => 'natural-flowers', 'name' => 'باقات ورد طبيعي', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'artificial-flowers', 'name' => 'ورد صناعي', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'chocolate', 'name' => 'باقات شوكولاتة', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'boxes', 'name' => 'صناديق الهدايا', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'full-packages', 'name' => 'هدايا متكاملة', 'thumbnail' => 'public/favicon.png'],
        ['slug' => 'teddy', 'name' => 'دباديب', 'thumbnail' => 'public/favicon.png'],
    ];
}
?>

<section class="collections-section">
    <div class="collections-inner">
        <header class="collections-header">
            <h2 class="collections-title">تشكيلات جاهزة</h2>
            <p class="collections-subtitle">
                مجموعات جاهزة من الورود، الشوكولاتة، الصناديق والدباديب، تختارها بضغطة واحدة وتجهّز لك للإهداء.
            </p>
        </header>

        <div class="collections-track-wrapper">
            <button class="collections-nav collections-nav--prev" type="button" aria-label="السابق">
                ‹
            </button>
            <button class="collections-nav collections-nav--next" type="button" aria-label="التالي">
                ›
            </button>

            <div class="collections-track">
                <?php $i = 0; ?>
                <?php foreach ($collections as $col): ?>
                    <?php 
                    $description = $collectionDescriptions[$col['slug']] ?? ($col['description'] ?? '');
                    $imgSrc = $col['thumbnail'] ?? 'public/favicon.png';
                    ?>
                    <a href="projects.php?category=<?php echo htmlspecialchars($col['slug']); ?>"
                       class="collection-card"
                       data-index="<?php echo $i++; ?>">

                        <div class="collection-card-bg"></div>

                        <div class="collection-card-image">
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>"
                                 alt="<?php echo htmlspecialchars($col['name']); ?>">
                            <span class="collection-card-pill">
                                تشكيلة جاهزة
                            </span>
                        </div>

                        <div class="collection-card-body">
                            <h3 class="collection-card-title">
                                <?php echo htmlspecialchars($col['name']); ?>
                            </h3>
                            <p class="collection-card-text">
                                <?php echo htmlspecialchars($description); ?>
                            </p>

                            <div class="collection-card-footer">
                                <span class="collection-card-cta">
                                    عرض التشكيلة
                                </span>
                                <span class="collection-card-dot"></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>