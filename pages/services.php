<?php
$pageTitle = "خدماتنا - من أجلك للهدايا والتنسيقات";
$pageExtraCss = '
    <link rel="stylesheet" href="public/css/services-gallery.css">
';
$pageExtraJs = '<script src="public/js/services-gallery.js" defer></script>';
require_once BASE_PATH . '/templates/layouts/header.php';

// صور قسم معرض الصور / مختارات من أعمالنا
$beforeAfterImages = [
    "public/images/1.jpg",
    "public/images/2.jpg",
    "public/images/3.jpg",
    "public/images/4.jpg",
    "public/images/5.jpg",
    "public/images/6.jpg",
    "public/images/7.jpg"
];
?>

<!-- HERO: سلايدر الخدمات الخاصة بالهدايا والتنسيقات -->
<section class="hero services-hero-slider">
    <div class="hero-bg">
        <!-- يمكنك لاحقاً استبدال الخلفيات بصور بوكسات وهدايا -->
        <div class="hero-slide active" style="background-image:url('public/image_services/Architectural_design.jpg');"></div>
        <div class="hero-slide" style="background-image:url('public/image_services/Halls.JPG');"></div>
        <div class="hero-slide" style="background-image:url('public/image_services/Rooms.JPG');"></div>
        <div class="hero-slide" style="background-image:url('public/image_services/Baths.JPG');"></div>
    </div>

    <div class="hero-overlay"></div>

    <div class="hero-content">
        <div class="hero-kicker">
            تنسيقات حفلات · هدايا تخرج · هدايا مواليد · باقات ورد وشوكولاتة
        </div>

        <h1 class="hero-title">
            ننسّق لك لحظاتك <span>الأقرب للقلب</span><br>
            من أول فكرة حتى أجمل <span>هدية وتنسيق</span>
        </h1>

        <p class="hero-subtitle">
            في "من أجلك" نهتم بكل تفاصيل الهدايا والتغليف وتنسيق الحفلات؛
            من اختيار الألوان والعناصر، إلى إعداد بوكس متكامل يصنع لحظة لا تُنسى
            في أعياد الميلاد، التخرج، المواليد، والمناسبات الخاصة.
        </p>

        <div class="hero-actions">
            <button class="btn-primary scroll-to-services" type="button">
                استعرض خدمات الهدايا والتنسيقات
            </button>
            <button class="btn-outline" type="button" onclick="window.location.href='contact.php'">
                اطلب تنسيق أو هدية خاصة
            </button>
        </div>

        <div class="hero-badges">
            <span>تنسيقات حسب ذوقك والمناسبة</span>
            <span>تغليف هدايا احترافي</span>
            <span>تفاصيل عناية من أول اتصال</span>
        </div>
    </div>
</section>

<!-- قسم معرض الخدمات -->
<section class="reveal services-gallery-section" id="services">
    <div class="section-wrapper">
        <div class="services-gallery-grid">
            
            <!-- خدمة 1: تنسيقات حفلات وأعياد ميلاد -->
            <div class="service-item" data-service="architectural">
                <div class="service-image-wrapper">
                    <img
                        src="public/image_services/Architectural_design.jpg"
                        class="service-main-image"
                        alt="تنسيقات حفلات وأعياد ميلاد"
                        loading="lazy"
                    >
                    <div class="service-overlay">
                        <button class="view-gallery-btn" type="button">عرض تنسيقات الحفلات</button>
                    </div>
                </div>
                <div class="service-content">
                    <h3 class="service-title">تنسيقات حفلات وأعياد ميلاد</h3>
                    <p class="service-description">
                        تجهيز طاولات ضيافة وبوكسات الهدايا وتنسيق البالونات والورد
                        لأعياد الميلاد والاحتفالات العائلية، بتصاميم وألوان تناسب عمر وشخصية صاحب المناسبة.
                    </p>
                </div>
            </div>

            <!-- خدمة 2: هدايا تخرج -->
            <div class="service-item" data-service="rooms">
                <div class="service-image-wrapper">
                    <img
                        src="public/image_services/Rooms.JPG"
                        class="service-main-image"
                        alt="هدايا وتنسيقات التخرج"
                        loading="lazy"
                    >
                    <div class="service-overlay">
                        <button class="view-gallery-btn" type="button">عرض هدايا التخرج</button>
                    </div>
                </div>
                <div class="service-content">
                    <h3 class="service-title">هدايا وتنسيقات التخرج</h3>
                    <p class="service-description">
                        بوكسات تخرج أنيقة، قبعات، ورود، وشوكولاتة بتغليف فاخر،
                        مع إمكانية إضافة عبارات تهنئة وأسماء الخريجين بطريقة مميزة.
                    </p>
                </div>
            </div>

            <!-- خدمة 3: هدايا مواليد -->
            <div class="service-item" data-service="baths">
                <div class="service-image-wrapper">
                    <img
                        src="public/image_services/Baths.JPG"
                        class="service-main-image"
                        alt="هدايا وتوزيعات مواليد"
                        loading="lazy"
                    >
                    <div class="service-overlay">
                        <button class="view-gallery-btn" type="button">عرض هدايا المواليد</button>
                    </div>
                </div>
                <div class="service-content">
                    <h3 class="service-title">هدايا وتوزيعات مواليد</h3>
                    <p class="service-description">
                        تنسيقات ناعمة للمواليد، بوكسات تهنئة، وتوزيعات مخصّصة بالاسم أو الحرف،
                        بألوان هادئة وتصاميم تناسب جو المناسبة وذوق العائلة.
                    </p>
                </div>
            </div>

            <!-- خدمة 4: هدايا المناسبات الخاصة -->
            <div class="service-item" data-service="toilet">
                <div class="service-image-wrapper">
                    <img
                        src="public/image_services/For a toilet (bathroom).JPG"
                        class="service-main-image"
                        alt="هدايا المناسبات الخاصة"
                        loading="lazy"
                    >
                    <div class="service-overlay">
                        <button class="view-gallery-btn" type="button">عرض هدايا المناسبات</button>
                    </div>
                </div>
                <div class="service-content">
                    <h3 class="service-title">هدايا المناسبات الخاصة</h3>
                    <p class="service-description">
                        هدايا مصممة لمناسبات مثل الخطوبة، الزواج، عيد الأم، أو أي مناسبة خاصة،
                        مع إمكانية تنسيق أكثر من عنصر في بوكس واحد: ورد، عطور، شوكولاتة، وهدايا شخصية.
                    </p>
                </div>
            </div>

            <!-- خدمة 5: باقات ورد وشوكولاتة -->
            <div class="service-item" data-service="halls">
                <div class="service-image-wrapper">
                    <img
                        src="public/image_services/Halls.JPG"
                        class="service-main-image"
                        alt="باقات ورد وشوكولاتة"
                        loading="lazy"
                    >
                    <div class="service-overlay">
                        <button class="view-gallery-btn" type="button">عرض باقات الورد والشوكولاتة</button>
                    </div>
                </div>
                <div class="service-content">
                    <h3 class="service-title">باقات ورد وشوكولاتة</h3>
                    <p class="service-description">
                        تنسيقات ورد طبيعي أو صناعي مع شوكولاتة مختارة بعناية،
                        في بوكسات أو باقات جاهزة للإهداء، بتغليف أنيق يليق بالمُهدى له.
                    </p>
                </div>
            </div>

            <!-- خدمة 6: معرض مختارات من أعمالنا (بدون ذكر قبل/بعد) -->
            <div class="service-item" data-service="beforeafter">
                <div class="service-image-wrapper">
                    <img
                        src="public/images/1.jpg"
                        class="service-main-image"
                        alt="مختارات من تنسيقاتنا وهدايانا"
                        loading="lazy"
                    >
                    <div class="service-overlay">
                        <button class="view-gallery-btn" type="button">استعرض مختاراتنا بالصور</button>
                    </div>
                </div>

                <div class="service-content">
                    <h3 class="service-title">معرض مختارات من أعمالنا</h3>
                    <p class="service-description">
                        مجموعة صور منتقاة من أجمل البوكسات والتنسيقات التي نفذناها
                        لمختلف المناسبات: أعياد ميلاد، تخرج، مواليد، مناسبات خاصة، وحفلات صغيرة.
                    </p>
                </div>

                <!-- JSON الخاص بالصور (يقرؤه services-gallery.js) -->
                <script type="application/json" class="service-data">
                    <?= json_encode($beforeAfterImages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
                </script>
            </div>

        </div>
    </div>
</section>

<!-- مودال المعرض -->
<div class="gallery-modal" id="galleryModal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <button class="modal-close" id="modalClose" type="button">
            ×
        </button>
        <div class="modal-header">
            <h2 class="modal-title" id="modalTitle">معرض الصور</h2>
            <p class="modal-subtitle" id="modalSubtitle">
                تصفّح أجمل تنسيقات الهدايا والحفلات، واضغط على أي صورة لعرضها بالحجم الكامل والتنقل بينها
            </p>
        </div>
        <div class="gallery-grid" id="galleryGrid"></div>
    </div>
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <div class="lightbox-overlay"></div>

    <button class="lightbox-close" id="lightboxClose" type="button">
        ×
    </button>

    <!-- في الاتجاه العربي: الزر الأيسر = الصورة التالية، الأيمن = السابقة -->
    <button class="lightbox-nav lightbox-prev" id="lightboxPrev" type="button">
        ‹
    </button>
    <button class="lightbox-nav lightbox-next" id="lightboxNext" type="button">
        ›
    </button>

    <div class="lightbox-content">
        <img id="lightboxImage" alt="">
        <div class="lightbox-counter" id="lightboxCounter">1 / 1</div>
    </div>
</div>

<?php require_once BASE_PATH . '/templates/layouts/footer.php'; ?>