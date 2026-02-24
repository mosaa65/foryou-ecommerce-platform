/* =========================================================
   🏢 تحسينات صفحة من نحن - About Page
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {
    initRevealAnimations();
    initCounterAnimation();
    initClientLogos();
});

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

// أنيميشن العد للأرقام
function initCounterAnimation() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                animateCounter(entry.target);
            }
        });
    }, {
        threshold: 0.5
    });
    
    statNumbers.forEach(stat => {
        counterObserver.observe(stat);
    });
}

// أنيميشن العداد
function animateCounter(element) {
    const target = parseInt(element.textContent.replace(/\D/g, ''));
    const duration = 2000; // 2 seconds
    const increment = target / (duration / 16); // 60fps
    let current = 0;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = formatNumber(target);
            clearInterval(timer);
        } else {
            element.textContent = formatNumber(Math.floor(current));
        }
    }, 16);
}

// تنسيق الأرقام
function formatNumber(num) {
    if (num >= 1000) {
        return num.toLocaleString('ar-SA');
    }
    return num.toString();
}

// تحسين شعارات العملاء
function initClientLogos() {
    const clientLogos = document.querySelectorAll('.client-logo');
    
    clientLogos.forEach(logo => {
        logo.addEventListener('mouseenter', () => {
            logo.style.transform = 'translateY(-6px) scale(1.02)';
        });
        
        logo.addEventListener('mouseleave', () => {
            logo.style.transform = 'translateY(0) scale(1)';
        });
    });
}

console.log('✅ صفحة من نحن جاهزة!');

