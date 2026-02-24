<?php
/**
 * دالة تجيب مجموعة صور للركائز من مجلدات المنتجات داخل public/gifts/{folder}
 * تمرّ على المجلدات 1، 2، 3، ... وتأخذ أول صورة من كل مجلد حتى حد معيّن.
 * ترجعها كسلسلة مفصولة بفواصل لتوضع في data-images.
 */
if (!function_exists('getPillarImagesForFolder')) {
    function getPillarImagesForFolder(string $folder, int $maxImages = 6): string
    {
        // المسار الفعلي: C:\xampp\htdocs\For You\assets\gifts\{folder}
        $baseDir = __DIR__ . '/../public/gifts/' . $folder;

        if (!is_dir($baseDir)) {
            return '';
        }

        // جميع مجلدات المنتجات الداخلية (1, 2, 3, ...)
        $productDirs = glob($baseDir . '/*', GLOB_ONLYDIR);
        if (!$productDirs) {
            return '';
        }

        natsort($productDirs);

        $imagesList = [];
        foreach ($productDirs as $productDir) {
            // نبحث عن أول صورة بأي امتداد شائع
            $files = glob(
                $productDir . '/*.{jpg,jpeg,png,webp,gif,JPG,JPEG,PNG,WEBP,GIF}',
                GLOB_BRACE
            );

            if ($files && isset($files[0])) {
                $imageFile      = basename($files[0]);   // اسم الصورة
                $productDirName = basename($productDir); // رقم المجلد (1، 2، 3، ...)

                // مسار URL نسبي: public/gifts/{folder}/{productDirName}/{imageFile}
                $webPath = 'public/gifts/' . $folder . '/' . $productDirName . '/' . $imageFile;

                $imagesList[] = $webPath;

                if (count($imagesList) >= $maxImages) {
                    break;
                }
            }
        }

        return implode(',', $imagesList);
    }
}
?>

<section class="pillars-section" id="pillars">
    <div class="pillars-orbits">
        <span class="orbit-circle circle-sm"></span>
        <span class="orbit-circle circle-md"></span>
        <span class="orbit-circle circle-lg"></span>
    </div>

    <div class="section-wrapper">
        <div class="pillars-header">
            <span class="eyebrow">4 ركائز تميز متجر من أجلك</span>
            <h2 class="service-quick-title">قيم نلتزم بها في كل هدية</h2>
            <p>
                نؤمن أن الهدية ليست مجرد منتج، بل تجربة كاملة تبدأ من اختيار القطع وتنتهي
                بابتسامة المستلم. هذه الركائز الأربع هي جوهر عملنا وتمثل المعايير التي
                نطبّقها في كل بوكس وهديّة يتم تجهيزها لك.
            </p>
        </div>

        <div class="pillars-grid">

            <!-- الركيزة 1: اختيار وتنسيق متقن (من مجلد boxes) -->
            <article class="pillar-card"
                     data-pillar
                     data-images="<?php echo htmlspecialchars(getPillarImagesForFolder('boxes', 6)); ?>">

                <div class="pillar-media">
                    <div class="pillar-media-img"></div>
                    <div class="pillar-media-overlay"></div>
                    <div class="pillar-media-label">تنسيق هدايا باهتمام كامل</div>
                    <div class="pillar-media-tag">عرض صور تلقائي</div>
                </div>

                <div class="pillar-icon">
                    <div class="icon-orbit"></div>
                    <div class="icon-core"><span class="dot"></span></div>
                </div>

                <span class="pill">الركيزة الأولى</span>
                <h3 class="card-title">اختيار دقيق وتنسيق متقن</h3>
                <p>
                    نحرص على اختيار قطع فاخرة ومتكاملة داخل كل بوكس، مع تنسيق ألوان
                    وتفاصيل تناسب المناسبة وتليق بذوقك وشخصك.
                </p>
                <ul class="pillar-points">
                    <li>تناسق مثالي بين مكونات الهدية</li>
                    <li>اختيار قطع عالية الجودة</li>
                    <li>تنسيق فخم يعكس المناسبة</li>
                </ul>
                <div class="card-badge">ذوق • تنسيق • فخامة</div>
            </article>

            <!-- الركيزة 2: التغليف الفاخر (من مجلد full-packages) -->
            <article class="pillar-card"
                     data-pillar
                     data-images="<?php echo htmlspecialchars(getPillarImagesForFolder('artificial-flowers', 6)); ?>">

                <div class="pillar-media">
                    <div class="pillar-media-img"></div>
                    <div class="pillar-media-overlay"></div>
                    <div class="pillar-media-label">تغليف فاخر يناسب كل مناسبة</div>
                    <div class="pillar-media-tag">تصاميم متعددة</div>
                </div>

                <div class="pillar-icon">
                    <div class="icon-orbit orbit-delay"></div>
                    <div class="icon-core"><span class="dot"></span></div>
                </div>

                <span class="pill">الركيزة الثانية</span>
                <h3 class="card-title">تغليف أنيق بلمسة فاخرة</h3>
                <p>
                    كل هدية يتم تغليفها بعناية فائقة باستخدام خامات فخمة
                    مثل الورق اللؤلؤي والشرائط الذهبية لتصل بأبهى صورة.
                </p>
                <ul class="pillar-points">
                    <li>خامات تغليف راقية</li>
                    <li>لمسات ذهبية وبيج وأحمر</li>
                    <li>مظهر فاخر جاهز للتقديم</li>
                </ul>
                <div class="card-badge">جمال • أناقة • فخامة</div>
            </article>

            <!-- الركيزة 3: تجربة العميل (مثلاً من natural-flowers) -->
            <article class="pillar-card"
                     data-pillar
                     data-images="<?php echo htmlspecialchars(getPillarImagesForFolder('natural-flowers', 6)); ?>">

                <div class="pillar-media">
                    <div class="pillar-media-img"></div>
                    <div class="pillar-media-overlay"></div>
                    <div class="pillar-media-label">خدمة عملاء راقية</div>
                    <div class="pillar-media-tag">تحديثات فورية</div>
                </div>

                <div class="pillar-icon">
                    <div class="icon-orbit orbit-fast"></div>
                    <div class="icon-core"><span class="dot"></span></div>
                </div>

                <span class="pill">الركيزة الثالثة</span>
                <h3 class="card-title">تجربة عميل استثنائية</h3>
                <p>
                    نقدم تجربة تواصل سريعة وواضحة، مع اقتراحات تناسب ميزانيتك
                    ورسائل متابعة لضمان رضاك التام قبل وبعد تجهيز الهدية.
                </p>
                <ul class="pillar-points">
                    <li>استجابة سريعة من الفريق</li>
                    <li>اقتراحات وفق المناسبة</li>
                    <li>عناية ما بعد التسليم</li>
                </ul>
                <div class="card-badge">راحة • ثقة • دعم</div>
            </article>

            <!-- الركيزة 4: الإبداع في التفاصيل (من مجلد chocolate) -->
            <article class="pillar-card"
                     data-pillar
                     data-images="<?php echo htmlspecialchars(getPillarImagesForFolder('chocolate', 6)); ?>">

                <div class="pillar-media">
                    <div class="pillar-media-img"></div>
                    <div class="pillar-media-overlay"></div>
                    <div class="pillar-media-label">هدايا مصممة بذوق عالٍ</div>
                    <div class="pillar-media-tag">لمسات فنية</div>
                </div>

                <div class="pillar-icon">
                    <div class="icon-orbit orbit-fast"></div>
                    <div class="icon-core"><span class="dot"></span></div>
                </div>

                <span class="pill">الركيزة الرابعة</span>
                <h3 class="card-title">إبداع في التفاصيل</h3>
                <p>
                    نهتم باللمسات الصغيرة مثل طريقة ترتيب القطع، كتابة بطاقة الإهداء،
                    وإضافة عناصر تجعل كل هدية تحمل قيمة خاصة لا تُنسى.
                </p>
                <ul class="pillar-points">
                    <li>تنسيق مميز لكل بوكس</li>
                    <li>بطاقة إهداء مصممة لك</li>
                    <li>تفاصيل تضيف قيمة للجمال</li>
                </ul>
                <div class="card-badge">إبداع • تميز • لمسة خاصة</div>
            </article>

        </div>
    </div>
</section>