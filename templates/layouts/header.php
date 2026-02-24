<?php
if (!isset($pageTitle))    $pageTitle    = "من أجلك للهدايا";
if (!isset($pageExtraCss)) $pageExtraCss = "";
if (!isset($pageExtraJs))  $pageExtraJs  = "";

// 🔧 غيّر هذا الدومين لدومينك الفعلي
$siteUrl = SITE_URL;
$currentPath = htmlspecialchars(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$fullUrl = $siteUrl . $currentPath;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">

    <!-- عنوان الصفحة + اسم المتجر لنتائج البحث -->
    <title>
        <?php echo htmlspecialchars($pageTitle) . ' | من أجلك للهدايا'; ?>
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO أساسي لمتجر من أجلك للهدايا -->
    <meta name="description" content="من أجلك للهدايا: متجر هدايا فاخرة يقدم بوكسات مميزة وتغليف راقٍ لكل المناسبات مع خدمة تنسيق خاصة وتوصيل داخل اليمن.">
    <meta name="keywords" content="محل هدايا, متجر هدايا, هدايا فاخرة, هدايا مميزة, بوكس هدايا, تنسيق هدايا, تغليف هدايا, هدايا رجالية, هدايا نسائية, هدايا أطفال, هدايا مواليد, هدايا تخرج, هدايا عيد ميلاد, هدايا رومانسية, هدايا للزوج, هدايا للزوجة, صندوق هدايا, متجر من أجلك للهدايا, هدايا اليمن, هدايا إب">
    <meta name="robots" content="index, follow">
    <meta name="author" content="من أجلك للهدايا">

    <!-- رابط قانوني لكل صفحة -->
    <link rel="canonical" href="<?php echo $fullUrl; ?>">

    <!-- أيقونة وشعار الموقع -->
    <link rel="icon" type="image/png" href="public/favicon.png">
    <link rel="shortcut icon" href="public/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="public/favicon.png">

    <!-- بيانات المشاركة في السوشيال (Open Graph) -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="من أجلك للهدايا">
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle) . ' | من أجلك للهدايا'; ?>">
    <meta property="og:description" content="من أجلك للهدايا: هدايا فاخرة، بوكسات مميزة، وتغليف راقٍ لكل المناسبات مع خدمة تنسيق خاصة.">
    <meta property="og:image" content="<?php echo $siteUrl; ?>/public/campne_name.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="<?php echo $fullUrl; ?>">
    <meta property="og:locale" content="ar_YE">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle) . ' | من أجلك للهدايا'; ?>">
    <meta name="twitter:description" content="من أجلك للهدايا: هدايا فاخرة، بوكسات مميزة، وتغليف راقٍ لكل المناسبات.">
    <meta name="twitter:image" content="<?php echo $siteUrl; ?>/public/campne_name.png">

    <!-- Google Fonts: Tajawal (الخط الرئيسي للموقع) مع تحسينات -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" 
          href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" 
          as="style" 
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    </noscript>

    <!-- ملف ستايل رئيسي للموقع -->
    <link rel="stylesheet" href="public/css/main.css">

    <!-- Font Awesome للأيقونات - مع defer -->
    <link rel="preload" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </noscript>

    <!-- أي CSS إضافي من الصفحات الداخلية -->
    <?php echo $pageExtraCss; ?>

    <!-- سكربت الهيدر (مؤجل لعدم تعطيل تحميل الصفحة) -->
    <script src="public/js/header-scroll.js" defer></script>

    <!-- أي JS إضافي من الصفحات الداخلية -->
    <?php echo $pageExtraJs; ?>
</head>
<body>
<header class="site-header" role="banner">
    <div class="header-inner">
        <!-- شعار الموقع -->
        <div class="logo">
            <a href="index.php">
                <img src="public/favicon.png" alt="شعار من أجلك للهدايا" class="logo-img">
                <div class="logo-text">
                    <div class="logo-text-main">من أجلك</div>
                    <div class="logo-text-sub">متجر هدايا وتغليف فاخر</div>
                </div>
            </a>
        </div>

        <!-- روابط سطح المكتب -->
        <nav class="nav-desktop" role="navigation" aria-label="القوائم الرئيسية">
            <ul>
                <li><a href=".">الرئيسية</a></li>
                <li><a href="about">من نحن</a></li>
                <li><a href="products">معرض الهدايا</a></li>
                <li><a href="contact">تواصل معنا</a></li>
            </ul>
        </nav>

        <!-- أزرار الهيدر (الثيم + القائمة) -->
        <div class="header-actions">
            <button class="theme-toggle-btn" type="button" aria-label="تبديل نمط الألوان">🌓</button>

            <!-- زر القائمة في الموبايل -->
            <button class="menu-toggle" type="button" aria-label="فتح القائمة" aria-controls="mobile-nav" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    <!-- قائمة الجوال -->
    <nav id="mobile-nav" class="nav-mobile" role="navigation" aria-label="القائمة الجانبية" aria-hidden="true">
        <ul>
            <li><a href=".">الرئيسية</a></li>
            <li><a href="about">من نحن</a></li>
            <li><a href="products">معرض الهدايا</a></li>
            <li><a href="contact">تواصل معنا</a></li>
        </ul>
    </nav>
</header>

<main>
