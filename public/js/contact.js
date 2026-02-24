/* =========================================================
   📞 تحسينات صفحة التواصل - Contact Page
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {
    initContactForm();
    initRevealAnimations();
    initPhoneFormatting();
});

// تحسين النموذج
function initContactForm() {
    const form = document.getElementById('contactForm');
    if (!form) return;

    const inputs = form.querySelectorAll('input, textarea, select');
    const submitBtn = form.querySelector('.form-submit-btn');

    // إضافة تأثير Focus
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', () => {
            if (!input.value) {
                input.parentElement.classList.remove('focused');
            }
        });

        // التحقق من صحة البيانات أثناء الكتابة
        input.addEventListener('input', () => {
            validateField(input);
        });
    });

    // تحسين زر الإرسال
    form.addEventListener('submit', (e) => {
        const isValid = validateForm(form);
        
        if (!isValid) {
            e.preventDefault();
            showError('يرجى تعبئة جميع الحقول المطلوبة بشكل صحيح');
            return;
        }

        // إظهار حالة التحميل
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>جاري الإرسال...</span>';
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';

            // إعادة الزر بعد 3 ثوان (في حالة فشل الإرسال)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }, 3000);
        }
    });
}

// التحقق من صحة الحقل
function validateField(field) {
    const value = field.value.trim();
    const fieldGroup = field.parentElement;

    // إزالة رسائل الخطأ السابقة
    const existingError = fieldGroup.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }

    fieldGroup.classList.remove('error', 'success');

    // التحقق حسب نوع الحقل
    if (field.hasAttribute('required') && !value) {
        return false;
    }

    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(fieldGroup, 'البريد الإلكتروني غير صحيح');
            return false;
        }
    }

    if (field.type === 'tel' && value) {
        const phoneRegex = /^(\+966|0)?5\d{8}$/;
        if (!phoneRegex.test(value.replace(/\s/g, ''))) {
            showFieldError(fieldGroup, 'رقم الجوال غير صحيح');
            return false;
        }
    }

    if (value) {
        fieldGroup.classList.add('success');
    }

    return true;
}

// التحقق من صحة النموذج كاملاً
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });

    return isValid;
}

// إظهار خطأ في حقل معين
function showFieldError(fieldGroup, message) {
    fieldGroup.classList.add('error');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    errorDiv.style.color = '#ef4444';
    errorDiv.style.fontSize = '0.85rem';
    errorDiv.style.marginTop = '4px';
    
    fieldGroup.appendChild(errorDiv);
}

// إظهار رسالة خطأ عامة
function showError(message) {
    const form = document.getElementById('contactForm');
    if (!form) return;

    // إزالة الرسائل السابقة
    const existingError = form.querySelector('.form-error');
    if (existingError) {
        existingError.remove();
    }

    const errorDiv = document.createElement('div');
    errorDiv.className = 'form-error';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        background: #fee2e2;
        color: #dc2626;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #fecaca;
        font-size: 0.9rem;
        text-align: center;
    `;

    form.insertBefore(errorDiv, form.firstChild);

    // إزالة الرسالة بعد 5 ثوان
    setTimeout(() => {
        errorDiv.style.opacity = '0';
        errorDiv.style.transition = 'opacity 0.3s ease';
        setTimeout(() => errorDiv.remove(), 300);
    }, 5000);
}

// تنسيق رقم الجوال
function initPhoneFormatting() {
    const phoneInput = document.getElementById('phone');
    if (!phoneInput) return;

    phoneInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, ''); // إزالة كل شيء ما عدا الأرقام

        // إضافة +966 تلقائياً إذا بدأ بـ 0
        if (value.startsWith('0')) {
            value = '966' + value.substring(1);
        }

        // تنسيق الرقم
        if (value.length > 0) {
            if (value.startsWith('966')) {
                value = '+966 ' + value.substring(3);
            } else if (!value.startsWith('+')) {
                value = value;
            }
        }

        e.target.value = value;
    });
}

// تفعيل أنيميشن الظهور
function initRevealAnimations() {
    const revealElements = document.querySelectorAll('.reveal');
    
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    revealElements.forEach(element => {
        revealObserver.observe(element);
    });
}

// تحسين تجربة المستخدم على الموبايل
if ('ontouchstart' in window) {
    document.body.classList.add('touch-device');
    
    // منع zoom عند التركيز على الحقول
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        if (input.style.fontSize !== '16px') {
            input.style.fontSize = '16px';
        }
    });
}

console.log('✅ صفحة التواصل جاهزة!');

