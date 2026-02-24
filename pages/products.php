<?php
/**
 * صفحة المعرض - عرض المنتجات من قاعدة البيانات
 * متجر "من أجلك" للهدايا
 */
$pageTitle = "هدايانا - متجر من أجلك للهدايا";
$pageDescription = "استعرض معرض هدايانا المميزة: عيد ميلاد، تخرج، خطوبة، زواج، مواليد، باقات ورد، شوكولاتة، دباديب، عطور، ساعات، وغيرها الكثير.";
$pageExtraCss = '<link rel="stylesheet" href="public/css/projects.css">';
$pageExtraJs = '<script src="public/js/projects.js" defer></script>';

// تحديد الصورة المميزة للمشاركة
$pageOgImage = "public/images/og-gifts.jpg";
$pageOgImageAlt = "معرض هدايا متجر من أجلك";

require_once BASE_PATH . '/templates/layouts/header.php';

// الاتصال بقاعدة البيانات
// (تم تضمينها في index.php، ولكن للتأكيد)
require_once BASE_PATH . '/app/config/database.php';
$db = getDB();

// جلب الفئات والأقسام والمنتجات من قاعدة البيانات
$categories = [];
$subcategories = [];
$products = [];

if ($db) {
    try {
        // جلب الفئات النشطة
        $stmt = $db->query("
            SELECT id, name, slug, icon, description
            FROM categories 
            WHERE is_active = 1 
            ORDER BY sort_order, id
        ");
        $categoriesData = $stmt->fetchAll();
        
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = [
                'id'    => $cat['id'],
                'label' => $cat['name'],
                'slug'  => $cat['slug'],
                'icon'  => $cat['icon'],
                'description' => $cat['description']
            ];
        }
        
        // جلب الأقسام النشطة
        $stmt = $db->query("
            SELECT s.id, s.name, s.slug, s.icon, s.description, s.category_id, c.slug as category_slug
            FROM subcategories s
            JOIN categories c ON s.category_id = c.id
            WHERE s.is_active = 1 AND c.is_active = 1
            ORDER BY s.sort_order, s.id
        ");
        $subcategoriesData = $stmt->fetchAll();
        
        foreach ($subcategoriesData as $sub) {
            $subcategories[$sub['slug']] = [
                'id'            => $sub['id'],
                'label'         => $sub['name'],
                'slug'          => $sub['slug'],
                'category_slug' => $sub['category_slug'],
                'icon'          => $sub['icon'],
                'description'   => $sub['description']
            ];
        }
        
        // جلب المنتجات النشطة مع بيانات القسم والفئة
        $stmt = $db->query("
            SELECT 
                p.id, p.name, p.slug, p.description, p.price, p.image_path,
                s.name as subcategory_name, s.slug as subcategory_slug,
                c.name as category_name, c.slug as category_slug
            FROM products p
            JOIN subcategories s ON p.subcategory_id = s.id
            JOIN categories c ON s.category_id = c.id
            WHERE p.is_active = 1 AND s.is_active = 1 AND c.is_active = 1
            ORDER BY p.sort_order, p.created_at DESC
        ");
        $productsData = $stmt->fetchAll();
        
        foreach ($productsData as $prod) {
            $products[] = [
                'id'              => $prod['id'],
                'slug'            => $prod['slug'],
                'title'           => $prod['name'],
                'description'     => $prod['description'] ?: 'هدية مميزة من قسم ' . $prod['subcategory_name'],
                'price'           => $prod['price'],
                'category'        => $prod['category_name'],
                'category_slug'   => $prod['category_slug'],
                'subcategory'     => $prod['subcategory_name'],
                'subcategory_slug'=> $prod['subcategory_slug'],
                'image_path'      => $prod['image_path'],
                'images'          => $prod['image_path'] ? [$prod['image_path']] : []
            ];
        }
        
    } catch (PDOException $e) {
        error_log("Products Page Error: " . $e->getMessage());
    }
}

$totalProducts = count($products);

// بناء Schema.org للصور
$schemaImages = [];
foreach ($products as $product) {
    if (!empty($product['images'])) {
        foreach ($product['images'] as $index => $image) {
            $schemaImages[] = [
                '@type' => 'ImageObject',
                'url' => SITE_URL . '/' . $image,
                'name' => $product['title'] . ' - صورة ' . ($index + 1),
                'description' => $product['description'],
                'creditText' => 'متجر من أجلك للهدايا',
            ];
        }
    }
}

$gallerySchema = [
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'معرض هدايا متجر من أجلك',
    'description' => $pageDescription,
    'numberOfItems' => $totalProducts,
    'itemListElement' => array_map(function($product, $index) {
        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => [
                '@type' => 'Product',
                'name' => $product['title'],
                'description' => $product['description'],
                'image' => $product['image_path'] ? SITE_URL . '/' . $product['image_path'] : null,
                'offers' => $product['price'] ? [
                    '@type' => 'Offer',
                    'price' => $product['price'],
                    'priceCurrency' => 'SAR'
                ] : null
            ]
        ];
    }, array_slice($products, 0, 20), array_keys(array_slice($products, 0, 20)))
];

$breadcrumbSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'الرئيسية',
            'item' => SITE_URL . '/'
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'هدايانا',
            'item' => SITE_URL . '/projects.php'
        ]
    ]
];
?>

<!-- Schema.org JSON-LD -->
<script type="application/ld+json">
<?php echo json_encode($gallerySchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<script type="application/ld+json">
<?php echo json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<!-- Projects Section -->
<section class="reveal projects-section">
    <div class="section-wrapper">

        <!-- Filters -->
        <div class="projects-filters">
            <button class="filter-btn active" data-filter="all">
                <span>الكل</span>
                <span class="filter-count"><?php echo $totalProducts; ?></span>
            </button>

            <?php foreach ($categories as $slug => $cat):
                $count = count(array_filter($products, fn($p) => $p['category_slug'] === $slug));
                if ($count === 0) continue;
            ?>
                <button class="filter-btn" data-filter="<?php echo htmlspecialchars($slug); ?>">
                    <span><?php echo htmlspecialchars($cat['label']); ?></span>
                    <span class="filter-count"><?php echo $count; ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Projects Grid -->
        <div class="projects-grid" id="projectsGrid">
            <?php if (empty($products)): ?>
                <div class="projects-empty" style="display: block;">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                    </svg>
                    <h3>لا توجد منتجات حالياً</h3>
                    <p>سيتم إضافة منتجات جديدة قريباً</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $productIndex => $product): ?>
                    <?php $cover = $product['image_path'] ?: 'public/images/placeholder.png'; ?>
                    
                    <div class="project-card" 
                         data-category="<?php echo htmlspecialchars($product['category_slug']); ?>" 
                         data-project-id="<?php echo htmlspecialchars($product['slug']); ?>">

                        <div class="project-image-wrapper">
                            <?php 
                            $altText = $product['title'] . ' - ' . $product['category'] . ' - متجر من أجلك للهدايا';
                            $titleText = $product['title'] . ' | هدايا فاخرة';
                            ?>
                            <img 
                                src="<?php echo htmlspecialchars($cover); ?>" 
                                alt="<?php echo htmlspecialchars($altText); ?>"
                                title="<?php echo htmlspecialchars($titleText); ?>"
                                class="project-main-image"
                                loading="lazy"
                                decoding="async"
                                fetchpriority="<?php echo $productIndex < 4 ? 'high' : 'low'; ?>"
                            >

                            <div class="project-overlay">
                                <div class="project-info">
                                    <span class="project-category"><?php echo htmlspecialchars($product['subcategory']); ?></span>
                                    <?php if ($product['price']): ?>
                                        <span class="project-price">
                                            <?php echo number_format($product['price'], 0); ?> ر.س
                                        </span>
                                    <?php else: ?>
                                        <span class="project-images-count">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <polyline points="21 15 16 10 5 21"></polyline>
                                            </svg>
                                            <?php echo count($product['images']); ?> صورة
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <button class="view-project-btn" title="عرض تفاصيل الهدية">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    <span>عرض التفاصيل</span>
                                </button>
                            </div>
                        </div>

                        <div class="project-content">
                            <h3 class="project-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                            <p class="project-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            
                            <?php
                            // إنشاء رابط واتساب مع التفاصيل
                            $siteUrl = SITE_URL . '/'; // غيّر هذا لرابط موقعك الفعلي
                            $imageUrl = $siteUrl . $cover;
                            
                            $whatsappText = "🎁 *" . $product['title'] . "*\n\n";
                            $whatsappText .= "📝 " . $product['description'] . "\n\n";
                            if ($product['price']) {
                                $whatsappText .= "💰 السعر: " . number_format($product['price'], 0) . " ر.س\n\n";
                            }
                            $whatsappText .= "📸 صورة الهدية:\n" . $imageUrl . "\n\n";
                            $whatsappText .= "أرغب بالاستفسار عن هذه الهدية 💝";
                            
                            $whatsappLink = "https://wa.me/967772217218?text=" . rawurlencode($whatsappText);
                            ?>
                            
                            <a href="<?php echo $whatsappLink; ?>" 
                               class="project-whatsapp-btn" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               onclick="event.stopPropagation();">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                <span>اطلب عبر واتساب</span>
                            </a>
                        </div>

                        <!-- بيانات المنتج للمودال -->
                        <div class="project-data" style="display: none;">
                            <?php echo json_encode($product, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Empty State -->
        <div class="projects-empty" id="projectsEmpty" style="display: none;">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="M21 21l-4.35-4.35"></path>
            </svg>
            <h3>لا توجد هدايا في هذا التصنيف</h3>
            <p>جرب تصنيف آخر لعرض المزيد من الهدايا</p>
        </div>
    </div>
</section>

<!-- Project Modal -->
<div class="project-modal" id="projectModal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <button class="modal-close" id="modalClose">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="modal-header">
            <h2 class="modal-title" id="modalTitle">اسم الهدية</h2>
            <p class="modal-subtitle" id="modalSubtitle">وصف مختصر للهدية</p>
        </div>

        <div class="modal-gallery" id="modalGallery"></div>
    </div>
</div>

<!-- Image Lightbox -->
<div class="image-lightbox" id="imageLightbox">
    <div class="lightbox-overlay"></div>
    <button class="lightbox-close" id="lightboxClose">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
    <button class="lightbox-nav lightbox-prev" id="lightboxPrev">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>
    <button class="lightbox-nav lightbox-next" id="lightboxNext">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </button>
    <div class="lightbox-content">
        <img src="" alt="" id="lightboxImage">
        <div class="lightbox-counter" id="lightboxCounter">1 / 1</div>
    </div>
</div>

<?php require_once BASE_PATH . '/templates/layouts/footer.php'; ?>