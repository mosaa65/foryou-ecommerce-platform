-- =========================================================
-- جدول إعدادات الموقع
-- =========================================================
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    setting_type VARCHAR(50) DEFAULT 'text',
    description VARCHAR(255) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- إعداد الفئات المعروضة في رأس الصفحة (3 فئات فقط)
-- القيمة عبارة عن JSON array من IDs الفئات
-- =========================================================
INSERT INTO site_settings (setting_key, setting_value, setting_type, description) VALUES
('header_categories', '[]', 'json', 'الفئات المختارة للعرض في رأس الموقع (3 فئات كحد أقصى)');
