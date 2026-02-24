-- =========================================================
-- قاعدة بيانات متجر "من أجلك" للهدايا
-- MySQL Database Schema
-- =========================================================


USE if0_40859317_touchyflower;

-- =========================================================
-- جدول المستخدمين (المدراء)
-- =========================================================
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- جدول الفئات الرئيسية (مثل: هدايا مميزة، هدايا ورد)
-- =========================================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(100) DEFAULT 'fa-solid fa-gift',
    description TEXT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_slug (slug),
    INDEX idx_sort_order (sort_order),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- جدول الأقسام الفرعية (مثل: هدايا عيد ميلاد، هدايا تخرج)
-- =========================================================
CREATE TABLE IF NOT EXISTS subcategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    icon VARCHAR(100) DEFAULT 'fa-solid fa-box',
    description TEXT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_slug_per_category (category_id, slug),
    INDEX idx_category_id (category_id),
    INDEX idx_slug (slug),
    INDEX idx_sort_order (sort_order),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- جدول المنتجات (الهدايا)
-- =========================================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subcategory_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10, 2) NULL,
    image_path VARCHAR(255) NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    views_count INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_slug_per_subcategory (subcategory_id, slug),
    INDEX idx_subcategory_id (subcategory_id),
    INDEX idx_slug (slug),
    INDEX idx_sort_order (sort_order),
    INDEX idx_is_active (is_active),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- جدول صور المنتجات (للمنتجات متعددة الصور)
-- =========================================================
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    is_primary TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id),
    INDEX idx_is_primary (is_primary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- إدخال مستخدم مدير افتراضي
-- كلمة المرور: admin123
-- =========================================================
INSERT INTO admin_users (username, email, password_hash, full_name) VALUES
('admin', 'admin@foryou.com', '$2y$10$ebEdDhy90/wRSGEoMkIDreZulleYnurccXjW76XhoRlwHOe06xTL6', 'مدير النظام');

-- =========================================================
-- إدخال بيانات تجريبية للفئات
-- =========================================================
INSERT INTO categories (name, slug, icon, sort_order) VALUES
('هدايا ورد', 'flowers', 'fa-solid fa-seedling', 1),
('هدايا شوكولاتة', 'chocolate', 'fa-solid fa-cookie', 2),
('هدايا مميزة', 'special', 'fa-solid fa-star', 3),
('هدايا مناسبات', 'occasions', 'fa-solid fa-gift', 4),
('هدايا اكسسوارات', 'accessories', 'fa-solid fa-gem', 5),
('طباعة هدايا', 'printing', 'fa-solid fa-print', 6),
('تنسيق هدايا', 'packaging', 'fa-solid fa-box-open', 7);

-- =========================================================
-- إدخال بيانات تجريبية للأقسام الفرعية
-- =========================================================
INSERT INTO subcategories (category_id, name, slug, icon, sort_order) VALUES
-- هدايا ورد
(1, 'باقات ورد طبيعي', 'natural-flowers', 'fa-solid fa-leaf', 1),
(1, 'ورد صناعي', 'artificial-flowers', 'fa-solid fa-spa', 2),
-- هدايا شوكولاتة
(2, 'باقات شوكولاتة', 'chocolate', 'fa-solid fa-cookie-bite', 1),
-- هدايا مميزة
(3, 'هدايا عيد ميلاد', 'birthday', 'fa-solid fa-cake-candles', 1),
(3, 'هدايا تخرج', 'graduation', 'fa-solid fa-graduation-cap', 2),
(3, 'هدايا مواليد', 'newborn', 'fa-solid fa-baby', 3),
(3, 'هدايا خطوبة', 'engagement', 'fa-solid fa-ring', 4),
(3, 'هدايا زواج', 'wedding', 'fa-solid fa-heart', 5),
(3, 'هدايا عيد الأم', 'mothers-day', 'fa-solid fa-hand-holding-heart', 6),
-- هدايا مناسبات
(4, 'عطور', 'perfumes', 'fa-solid fa-spray-can-sparkles', 1),
(4, 'ساعات', 'watches', 'fa-regular fa-clock', 2),
(4, 'هدايا مكياج', 'makeup', 'fa-solid fa-paintbrush', 3),
(4, 'دباديب', 'teddy', 'fa-solid fa-paw', 4),
-- هدايا اكسسوارات
(5, 'اكسسوارات', 'accessories', 'fa-solid fa-gem', 1),
(5, 'هدايا مع جوالات', 'phones', 'fa-solid fa-mobile-screen', 2),
(5, 'ميداليات', 'keychains', 'fa-solid fa-key', 3),
-- طباعة هدايا
(6, 'طباعة أسماء', 'name-print', 'fa-solid fa-pen-fancy', 1),
(6, 'نحت خشب', 'wood-engraving', 'fa-solid fa-tree', 2),
(6, 'نقش خواتم', 'ring-engraving', 'fa-solid fa-ring', 3),
-- تنسيق هدايا
(7, 'صناديق هدايا', 'boxes', 'fa-solid fa-boxes-stacked', 1),
(7, 'هدايا متكاملة', 'full-packages', 'fa-solid fa-gifts', 2),
(7, 'تغليف الهدايا', 'wrapping', 'fa-solid fa-ribbon', 3),
(7, 'هدايا أخرى', 'other', 'fa-solid fa-ellipsis', 4);
