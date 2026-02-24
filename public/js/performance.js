/* =========================================================
   ⚡ تحسينات الأداء - Performance Optimizations
   متجر من أجلك للهدايا
   ========================================================= */

(function () {
    'use strict';

    // =========================================================
    // 🖼️ Lazy Loading للصور
    // =========================================================

    function initLazyLoading() {
        // إضافة loading="lazy" لكل الصور التي لا تملكها
        const images = document.querySelectorAll('img:not([loading])');
        images.forEach(img => {
            // لا نضيف lazy للصور في الـ Hero (أول 3 صور)
            if (!img.closest('.hero') && !img.closest('.site-header')) {
                img.setAttribute('loading', 'lazy');
                img.setAttribute('decoding', 'async');
            }
        });

        // Intersection Observer للصور مع تأثير fade
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        img.classList.add('loaded');
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    // =========================================================
    // 🎯 تحسين تحميل الخطوط
    // =========================================================

    function initFontOptimization() {
        // التحقق من تحميل الخطوط
        if ('fonts' in document) {
            document.fonts.ready.then(() => {
                document.body.classList.add('fonts-loaded');
            });
        } else {
            // fallback للمتصفحات القديمة
            setTimeout(() => {
                document.body.classList.add('fonts-loaded');
            }, 1000);
        }
    }

    // =========================================================
    // 📱 تحسين التمرير على الموبايل
    // =========================================================

    function initSmoothScroll() {
        // تحسين السكرول للروابط الداخلية
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // =========================================================
    // 🔄 Preload للصفحات المتوقعة
    // =========================================================

    function initLinkPreload() {
        // Preload الروابط عند hover
        document.querySelectorAll('a[href]').forEach(link => {
            let prefetchTimeout;

            link.addEventListener('mouseenter', function () {
                const href = this.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('http') || href.startsWith('mailto') || href.startsWith('tel')) {
                    return;
                }

                prefetchTimeout = setTimeout(() => {
                    const prefetch = document.createElement('link');
                    prefetch.rel = 'prefetch';
                    prefetch.href = href;
                    document.head.appendChild(prefetch);
                }, 100);
            });

            link.addEventListener('mouseleave', function () {
                clearTimeout(prefetchTimeout);
            });
        });
    }

    // =========================================================
    // 📊 تتبع Core Web Vitals (للتطوير)
    // =========================================================

    function logPerformance() {
        if (window.location.hostname === 'localhost') {
            // فقط في بيئة التطوير
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    if (perfData) {
                        console.log('📊 أداء الصفحة:');
                        console.log(`   DOM تحميل: ${Math.round(perfData.domContentLoadedEventEnd)}ms`);
                        console.log(`   تحميل كامل: ${Math.round(perfData.loadEventEnd)}ms`);
                    }
                }, 0);
            });
        }
    }

    // =========================================================
    // 🚀 التهيئة
    // =========================================================

    document.addEventListener('DOMContentLoaded', () => {
        initLazyLoading();
        initFontOptimization();
        initSmoothScroll();
        initLinkPreload();
        logPerformance();
    });

})();
