<?php
/**
 * صفحة 404 - الصفحة غير موجودة
 */
$pageTitle = "الصفحة غير موجودة";
require_once BASE_PATH . '/templates/layouts/header.php';
?>

<section class="error-page">
    <div class="error-wrapper">
        <div class="error-content">
            <div class="error-icon">
                <i class="fa-solid fa-gift-slash"></i>
            </div>
            
            <h1 class="error-code">404</h1>
            <h2 class="error-title">عذراً، الصفحة غير موجودة</h2>
            
            <p class="error-text">
                يبدو أن الصفحة التي تبحث عنها غير موجودة أو تم نقلها.<br>
                لا تقلق، يمكنك العودة للصفحة الرئيسية واستكشاف هدايانا الفاخرة.
            </p>
            
            <div class="error-actions">
                <a href="/" class="btn-primary">
                    <i class="fa-solid fa-home"></i>
                    العودة للرئيسية
                </a>
                <a href="/products" class="btn-outline">
                    <i class="fa-solid fa-gift"></i>
                    تصفح الهدايا
                </a>
            </div>
            
            <div class="error-suggestions">
                <h3>روابط مفيدة:</h3>
                <ul>
                    <li><a href="/about">من نحن</a></li>
                    <li><a href="/services">خدماتنا</a></li>
                    <li><a href="/contact">تواصل معنا</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<style>
.error-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem 1rem;
    background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
}

.error-wrapper {
    max-width: 600px;
    text-align: center;
}

.error-icon {
    font-size: 4rem;
    color: var(--primary-color, #c9a050);
    margin-bottom: 1.5rem;
    opacity: 0.8;
}

.error-code {
    font-size: 8rem;
    font-weight: 900;
    color: var(--primary-color, #c9a050);
    line-height: 1;
    margin: 0;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.error-title {
    font-size: 1.75rem;
    color: var(--text-primary, #333);
    margin: 1rem 0;
    font-weight: 600;
}

.error-text {
    color: var(--text-secondary, #666);
    font-size: 1.1rem;
    line-height: 1.8;
    margin-bottom: 2rem;
}

.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 2.5rem;
}

.error-actions .btn-primary,
.error-actions .btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.75rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.error-actions .btn-primary {
    background: var(--primary-color, #c9a050);
    color: white;
}

.error-actions .btn-primary:hover {
    background: var(--primary-hover, #b8913f);
    transform: translateY(-2px);
}

.error-actions .btn-outline {
    border: 2px solid var(--primary-color, #c9a050);
    color: var(--primary-color, #c9a050);
    background: transparent;
}

.error-actions .btn-outline:hover {
    background: var(--primary-color, #c9a050);
    color: white;
}

.error-suggestions {
    padding-top: 2rem;
    border-top: 1px solid var(--border-color, #eee);
}

.error-suggestions h3 {
    font-size: 1rem;
    color: var(--text-secondary, #666);
    margin-bottom: 1rem;
}

.error-suggestions ul {
    list-style: none;
    padding: 0;
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.error-suggestions a {
    color: var(--primary-color, #c9a050);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.error-suggestions a:hover {
    color: var(--primary-hover, #b8913f);
    text-decoration: underline;
}

@media (max-width: 600px) {
    .error-code {
        font-size: 5rem;
    }
    
    .error-title {
        font-size: 1.4rem;
    }
    
    .error-actions {
        flex-direction: column;
    }
    
    .error-suggestions ul {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

<?php require_once BASE_PATH . '/templates/layouts/footer.php'; ?>
