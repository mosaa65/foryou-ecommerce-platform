<?php
$serviceQuick = [

    // ============= هدايا رجالية =============
    [
        'category_slug'  => 'gift_men',
        'project_number' => '1',
        'label'          => 'هدايا رجالية',
        'title'          => 'بوكس رجالي فاخر',
        'text'           => 'تنسيق فاخر يحتوي على عطر رجالي، مسبحة مميزة، ومحفظة جلدية بتغليف أنيق مناسب للمناسبات الخاصة.'
    ],

    // ============= هدايا نسائية =============
    [
        'category_slug'  => 'gift_women',
        'project_number' => '1',
        'label'          => 'هدايا نسائية',
        'title'          => 'بوكس نسائي بتنسيق وردي',
        'text'           => 'هدية ناعمة تجمع بين العطر النسائي والشموع والورد المجفف بتنسيق فخم بلمسات بيج وذهبي.'
    ],

    // ============= هدايا مواليد =============
    [
        'category_slug'  => 'gift_baby',
        'project_number' => '1',
        'label'          => 'هدايا مواليد',
        'title'          => 'بوكس مواليد راقٍ',
        'text'           => 'هدية مخصصة للمواليد تضم قطعاً ناعمة، عطر أطفال، وبطاقة تهنئة مناسبة لاستقبال المولود.'
    ],

    // ============= هدايا مناسبات =============
    [
        'category_slug'  => 'gift_occasions',
        'project_number' => '2',
        'label'          => 'مناسبات',
        'title'          => 'بوكس تخرج مميز',
        'text'           => 'تنسيق احتفالي يحتوي على قطع رمزية للتخرج، عطر، وبطاقات تهنئة مصممة خصيصاً.'
    ],

    // ============= أعياد ميلاد =============
    [
        'category_slug'  => 'gift_occasions',
        'project_number' => '3',
        'label'          => 'عيد ميلاد',
        'title'          => 'بوكس عيد ميلاد فاخر',
        'text'           => 'هدية عيد ميلاد بتنسيق جميل يجمع بين الشوكولاتة والعطر والكرت المخصص.'
    ],

    // ============= هدايا منزلية =============
    [
        'category_slug'  => 'gift_home',
        'project_number' => '1',
        'label'          => 'هدايا منزل',
        'title'          => 'بوكس المنزل الراقي',
        'text'           => 'تنسيق منزل فاخر يحتوي على شموع فاخرة وقطع ديكور أنيقة تقدم كهدية ترحيبية مثالية.'
    ],

    // ============= Premium =============
    [
        'category_slug'  => 'gift_premium',
        'project_number' => '1',
        'label'          => 'Premium',
        'title'          => 'بوكس Premium الفاخر',
        'text'           => 'بوكس يضم أفخم القطع مثل بخور فاخر، عطر مميز، وإكسسوارات راقية داخل صندوق فاخر.'
    ],

    // ============= هدايا شركات =============
    [
        'category_slug'  => 'gift_business',
        'project_number' => '1',
        'label'          => 'هدايا شركات',
        'title'          => 'هدايا مؤسسية مخصصة',
        'text'           => 'تصميم هدايا شركات بلمسات فاخرة مع خيار إضافة شعار المؤسسة وتخصيص التغليف بالكامل.'
    ],

];
?>

<section class="services-quick">
    <div class="services-quick-inner">

        <div class="services-quick-header">
            <span class="sq-eyebrow">خدماتنا</span>
            <h2 class="service-quick-title">هدايا متنوعة تناسب جميع المناسبات</h2>
            <p class="sq-subtitle">
                نقدم مجموعة واسعة من الهدايا الجاهزة والمخصصة، بتنسيق فاخر وتغليف يليق
                بذوقك، مع إمكانية تخصيص المحتوى حسب الطلب.
            </p>
        </div>

        <div class="services-grid">

            <?php foreach ($serviceQuick as $card): ?>

                <?php
                // مجلد الصور
                $webFolder = "public/project_img/{$card['category_slug']}/{$card['project_number']}/";
                $fsFolder  = dirname(__DIR__) . '/' . $webFolder;

                $images = [];

                if (is_dir($fsFolder)) {
                    $files = scandir($fsFolder);

                    foreach ($files as $file) {
                        if ($file === '.' || $file === '..') continue;

                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                            $images[] = $webFolder . $file;
                        }
                    }
                }

                if (empty($images)) continue;

                $dataImages = htmlspecialchars(implode(',', $images), ENT_QUOTES, 'UTF-8');
                ?>

                <article class="service-card" data-images="<?php echo $dataImages; ?>">

                    <div class="service-image-slider">
                        <div class="service-slide"></div>
                        <button class="slider-nav prev">&#10094;</button>
                        <button class="slider-nav next">&#10095;</button>
                        <div class="slider-dots"></div>
                    </div>

                    <div class="service-label">
                        <?php echo htmlspecialchars($card['label']); ?>
                    </div>

                    <h3 class="service-quick-title">
                        <?php echo htmlspecialchars($card['title']); ?>
                    </h3>

                    <p class="service-text">
                        <?php echo htmlspecialchars($card['text']); ?>
                    </p>
                </article>

            <?php endforeach; ?>

        </div>

    </div>
</section>