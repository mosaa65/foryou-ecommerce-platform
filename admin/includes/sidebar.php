<?php
/**
 * القائمة الجانبية للوحة التحكم
 */
$menuItems = [
    [
        'slug' => 'index',
        'icon' => 'fa-solid fa-gauge-high',
        'label' => 'الرئيسية',
        'link' => 'index.php'
    ],
    [
        'slug' => 'categories',
        'icon' => 'fa-solid fa-layer-group',
        'label' => 'الفئات الرئيسية',
        'link' => 'categories.php'
    ],
    [
        'slug' => 'subcategories',
        'icon' => 'fa-solid fa-folder-tree',
        'label' => 'الأقسام الفرعية',
        'link' => 'subcategories.php'
    ],
    [
        'slug' => 'products',
        'icon' => 'fa-solid fa-gifts',
        'label' => 'المنتجات',
        'link' => 'products.php'
    ],
    [
        'slug' => 'header-settings',
        'icon' => 'fa-solid fa-heading',
        'label' => 'فئات رأس الموقع',
        'link' => 'header-settings.php'
    ],
];
?>

<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <a href="index.php" class="sidebar-logo">
            <img src="../public/favicon.png" alt="من أجلك">
            <div class="sidebar-logo-text">
                <span class="logo-main">من أجلك</span>
                <span class="logo-sub">لوحة التحكم</span>
            </div>
        </a>
        <button class="sidebar-close" id="sidebarClose">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            <?php foreach ($menuItems as $item): ?>
                <li class="sidebar-item <?php echo $currentPage === $item['slug'] ? 'active' : ''; ?>">
                    <a href="<?php echo $item['link']; ?>" class="sidebar-link">
                        <i class="<?php echo $item['icon']; ?>"></i>
                        <span><?php echo $item['label']; ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <div class="sidebar-divider"></div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="../index.php" target="_blank" class="sidebar-link">
                    <i class="fa-solid fa-globe"></i>
                    <span>عرض الموقع</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="logout.php" class="sidebar-link text-danger">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>تسجيل الخروج</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <p>الإصدار 1.0</p>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>