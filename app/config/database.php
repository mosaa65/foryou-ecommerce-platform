<?php
/**
 * ملف الاتصال بقاعدة البيانات MySQL
 * متجر "من أجلك" للهدايا
 */

class Database {
    private $host = "localhost";
    private $db_name = "foryou_gifts";
    private $username = "root";
    private $password = "";
    private $charset = "utf8mb4";
    
    public $conn;
    
    /**
     * إنشاء اتصال بقاعدة البيانات
     * @return PDO|null
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            // في بيئة التطوير يمكن إظهار الخطأ
            // echo "Connection Error: " . $e->getMessage();
        }
        
        return $this->conn;
    }
    
    /**
     * إغلاق الاتصال
     */
    public function closeConnection() {
        $this->conn = null;
    }
}

/**
 * دالة مساعدة للحصول على اتصال سريع
 * @return PDO|null
 */
function getDB() {
    static $db = null;
    
    if ($db === null) {
        $database = new Database();
        $db = $database->getConnection();
    }
    
    return $db;
}

/**
 * دالة لتنظيف المدخلات
 * @param string $data
 * @return string
 */
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * دالة لإنشاء slug من النص العربي/الإنجليزي
 * @param string $text
 * @return string
 */
function createSlug($text) {
    // تحويل للحروف الصغيرة
    $text = mb_strtolower($text, 'UTF-8');
    
    // استبدال المسافات بشرطات
    $text = preg_replace('/\s+/', '-', $text);
    
    // إزالة الأحرف غير المسموحة (نحتفظ بالعربية والإنجليزية والأرقام والشرطات)
    $text = preg_replace('/[^\p{Arabic}a-z0-9\-]/u', '', $text);
    
    // إزالة الشرطات المتكررة
    $text = preg_replace('/-+/', '-', $text);
    
    // إزالة الشرطات من البداية والنهاية
    $text = trim($text, '-');
    
    // إذا كان فارغاً، إنشاء slug عشوائي
    if (empty($text)) {
        $text = 'item-' . time();
    }
    
    return $text;
}

/**
 * جلب أول صورة منتج من قسم معين بناءً على slug
 * @param string $subcategorySlug - اسم القسم (مثل: birthday, flowers)
 * @return string - مسار الصورة أو صورة افتراضية
 */
function getSubcategoryThumbnail($subcategorySlug) {
    $fallback = 'assets/favicon.png';
    
    $db = getDB();
    if (!$db) {
        return $fallback;
    }
    
    try {
        $stmt = $db->prepare("
            SELECT p.image_path 
            FROM products p
            JOIN subcategories s ON p.subcategory_id = s.id
            WHERE s.slug = :slug 
              AND p.is_active = 1 
              AND s.is_active = 1
              AND p.image_path IS NOT NULL 
              AND p.image_path != ''
            ORDER BY p.sort_order ASC, p.id ASC
            LIMIT 1
        ");
        $stmt->execute(['slug' => $subcategorySlug]);
        $result = $stmt->fetch();
        
        if ($result && !empty($result['image_path'])) {
            return $result['image_path'];
        }
    } catch (PDOException $e) {
        error_log("getSubcategoryThumbnail Error: " . $e->getMessage());
    }
    
    return $fallback;
}

/**
 * جلب بيانات الأقسام الفرعية حسب category_id أو مجموعة slugs
 * @param array $slugs - قائمة slugs للأقسام المطلوبة
 * @return array - بيانات الأقسام مع صورها
 */
function getSubcategoriesBySlugs($slugs) {
    $db = getDB();
    if (!$db || empty($slugs)) {
        return [];
    }
    
    try {
        $placeholders = str_repeat('?,', count($slugs) - 1) . '?';
        $stmt = $db->prepare("
            SELECT s.id, s.name, s.slug, s.icon, s.description
            FROM subcategories s
            WHERE s.slug IN ($placeholders)
              AND s.is_active = 1
            ORDER BY s.sort_order ASC
        ");
        $stmt->execute($slugs);
        $subcategories = $stmt->fetchAll();
        
        // إضافة الصور لكل قسم
        foreach ($subcategories as &$sub) {
            $sub['thumbnail'] = getSubcategoryThumbnail($sub['slug']);
        }
        
        return $subcategories;
    } catch (PDOException $e) {
        error_log("getSubcategoriesBySlugs Error: " . $e->getMessage());
    }
    
    return [];
}

