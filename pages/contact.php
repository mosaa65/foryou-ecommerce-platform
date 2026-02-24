<?php
$pageTitle = "تواصل معنا - متجر من أجلك للهدايا";
$pageExtraCss = '<link rel="stylesheet" href="public/css/contact.css">';
$pageExtraJs = '<script src="public/js/contact.js" defer></script>';
require_once BASE_PATH . '/templates/layouts/header.php';
?>

<section class="reveal contact-hero">
    <div class="section-wrapper">
        <div class="contact-hero-content">
            <span class="hero-badge">تواصل معنا</span>
            <h1 class="hero-title">نسعد بخدمتكم في من أجلك</h1>
            <p class="hero-description">
                هل لديك مناسبة خاصة؟ تريد تنسيق هدية جميلة؟  
                فريقنا جاهز لمساعدتك واختيار الهدية المناسبة حسب ذوقك والمناسبة.  
                تواصل معنا عبر النموذج أو من خلال وسائل التواصل المباشرة.
            </p>
        </div>
    </div>
</section>

<section class="reveal contact-section">
    <div class="section-wrapper">
        <div class="contact-grid">

            <!-- معلومات التواصل -->
            <div class="contact-info-wrapper">
                <div class="info-header">
                    <h2 class="info-title">معلومات التواصل</h2>
                    <p class="info-subtitle">يمكنك التواصل معنا مباشرة عبر الوسائل التالية</p>
                </div>

                <div class="contact-cards">

                    <a href="tel:+967772217218" class="contact-card">
                        <div class="card-icon phone-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07c-2.63-1.87-4.73-4-6-6A19.79 19.79 0 0 1 2 4.11 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.1.96.32 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.38 1.85.6 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </div>

                        <div class="card-content">
                            <h3 class="card-title">الهاتف</h3>
                            <p class="card-value">+967 772217218</p>
                            <span class="card-hint">اضغط للاتصال</span>
                        </div>
                    </a>

                    <a href="mailto:mousa.mc13@gmail.com" class="contact-card">
                        <div class="card-icon email-icon">
                            <svg width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" viewBox="0 0 24 24">
                                <path d="M4 4h16a2 2 0 0 1 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6a2 2 0 0 1 2-2z"></path>
                                <polyline points="22 6 12 13 2 6"></polyline>
                            </svg>
                        </div>

                        <div class="card-content">
                            <h3 class="card-title">البريد الإلكتروني</h3>
                            <p class="card-value">mousa.mc13@gmail.com</p>
                            <span class="card-hint">اضغط لإرسال بريد</span>
                        </div>
                    </a>

                    <a href="https://wa.me/967772217218" target="_blank" class="contact-card whatsapp-card">
                        <div class="card-icon whatsapp-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.47 14.38c-.30-.15-1.76-.87-2.03-.97-.27-.10-.47-.15-.67.15-.20.30-.77.97-.94 1.16-.17.20-.35.22-.64.07-.30-.15-1.26-.46-2.39-1.48-.88-.79-1.48-1.76-1.65-2.06-.17-.30-.02-.46.13-.61.13-.13.30-.35.45-.52.15-.17.20-.30.30-.50.10-.20.05-.37-.02-.52-.08-.15-.67-1.61-.92-2.20-.24-.58-.49-.50-.67-.51-.17-.01-.37-.01-.57-.01-.20 0-.52.07-.79.37-.27.30-1.04 1.02-1.04 2.48 0 1.46 1.06 2.88 1.21 3.08.15.20 2.10 3.20 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.20 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.69.25-1.29.17-1.41-.07-.13-.27-.20-.57-.35z"/>
                            </svg>
                        </div>

                        <div class="card-content">
                            <h3 class="card-title">واتساب</h3>
                            <p class="card-value">+967 772217218</p>
                            <span class="card-hint">اضغط للدردشة</span>
                        </div>
                    </a>

                    <div class="contact-card location-card">
                        <div class="card-icon location-icon">
                            <svg width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>

                        <div class="card-content">
                            <h3 class="card-title">العنوان</h3>
                            <p class="card-value">اليمن</p>
                            <span class="card-hint">إب</span>
                        </div>
                    </div>
                </div>

                <div class="working-hours">
                    <h3 class="hours-title">ساعات العمل</h3>

                    <div class="hours-list">
                        <div class="hours-item">
                            <span class="hours-day">أيام الأسبوع</span>
                            <span class="hours-time">سبت – جمعة</span>
                        </div>

                        <div class="hours-item">
                            <span class="hours-day">أوقات العمل</span>
                            <span class="hours-time">متاحون 24 ساعة</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- نموذج التواصل -->
            <div class="contact-form-wrapper">
                <div class="form-header">
                    <h2 class="form-title">أرسل لنا رسالة</h2>
                    <p class="form-subtitle">نتشرف بخدمتك وتنسيق هديتك بالشكل الذي يناسب ذوقك ومناسبتك</p>
                </div>

                <form method="POST" action="contact-submit" class="contact-form" id="contactForm">

                    <div class="form-group">
                        <label for="name" class="form-label">
                            <span>الاسم الكامل</span>
                        </label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="أدخل اسمك" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <span>رقم الجوال</span>
                        </label>
                        <input type="text" id="phone" name="phone" class="form-input" placeholder="+967 712345678" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <span>البريد الإلكتروني (اختياري)</span>
                        </label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="mousa.mc13@gmail.com">
                    </div>

                    <div class="form-group">
                        <label for="service" class="form-label">
                            <span>نوع الخدمة المطلوبة</span>
                        </label>

                        <select id="service" name="service" class="form-select">
                            <option value="">اختر نوع الخدمة</option>
                            <option value="تنسيق هدايا">تنسيق هدايا</option>
                            <option value="تغليف فاخر">تغليف الهدايا</option>
                            <option value="هدايا شركات">باقات الشركات</option>
                            <option value="هدايا مناسبات">هدايا المناسبات الخاصة</option>
                            <option value="طباعة وتخصيص">طباعة أسماء ورسائل على الهدايا</option>
                            <option value="طلب خاص">طلب مخصص</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">
                            <span>تفاصيل الطلب</span>
                        </label>
                        <textarea id="message" name="message" class="form-textarea" placeholder="اكتب تفاصيل هديتك، المناسبة، الألوان المطلوبة، الميزانية..." rows="6" required></textarea>
                    </div>

                    <button type="submit" class="form-submit-btn">
                        <span>إرسال</span>
                    </button>

                </form>
            </div>

        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/templates/layouts/footer.php'; ?>