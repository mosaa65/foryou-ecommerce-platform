<?php
/**
 * صفحة العملاء - من أجلك للهدايا
 */
$pageTitle = "آراء عملائنا";
require_once BASE_PATH . '/templates/layouts/header.php';
?>

<section class="reveal">
    <div class="section-wrapper">
        <h1 class="section-title">آراء عملائنا</h1>
        <p class="section-subtitle">
            نعتز بثقة عملائنا الكرام، ونسعى دائمًا لتقديم هدايا وتجارب تفوق توقعاتهم.
        </p>

        <div class="testimonials" style="display: grid; gap: 20px; margin-top: 30px;">
            <div class="card" style="padding: 24px; text-align: center;">
                <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 12px;">
                    "تعاملت مع متجر من أجلك في مناسبة تخرج أختي، كانت الهدية رائعة والتغليف فاخر جداً. شكراً لكم!"
                </p>
                <span style="color: var(--gold); font-weight: 600;">— سارة م.</span>
            </div>
            
            <div class="card" style="padding: 24px; text-align: center;">
                <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 12px;">
                    "بوكس هدية الزواج كان مميز جداً، التنسيق والاختيارات كانت راقية. أنصح بالتعامل معهم."
                </p>
                <span style="color: var(--gold); font-weight: 600;">— محمد أ.</span>
            </div>
            
            <div class="card" style="padding: 24px; text-align: center;">
                <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 12px;">
                    "طلبت هدية مولود وكانت النتيجة أكثر من المتوقع! التوصيل سريع والتغليف احترافي."
                </p>
                <span style="color: var(--gold); font-weight: 600;">— فاطمة ع.</span>
            </div>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/templates/layouts/footer.php'; ?>