// assets/js/services-gallery.js
(function () {
    'use strict';

    // =========================================================
    // 📊 بيانات افتراضية للخدمات (تُستخدم مباشرة)
    // =========================================================
    const servicesData = {
        architectural: {
            title: 'التصميم المعماري',
            subtitle: 'تصاميم معمارية إبداعية تجمع بين الجمال والوظيفة',
            images: [
                'assets/image_services/Architectural design/Architectura_design2.JPG',
                'assets/image_services/Architectural design/IMG_0330.JPG',
                'assets/image_services/Architectural design/IMG_0332[1].JPG',
                'assets/image_services/Architectural design/IMG_0333[1].JPG'
            ]
        },
        rooms: {
            title: 'تصميم الغرف',
            subtitle: 'غرف نوم راقية وعصرية توفر أجواء من الراحة والاسترخاء',
            images: [
                'assets/image_services/Rooms/IMG_0281[1].JPG',
                'assets/image_services/Rooms/IMG_0282.JPG',
                'assets/image_services/Rooms/IMG_0282[1].JPG',
                'assets/image_services/Rooms/IMG_0283.JPG',
                'assets/image_services/Rooms/IMG_0288.JPG',
                'assets/image_services/Rooms/IMG_0293.JPG'
            ]
        },
        baths: {
            title: 'تصميم الحمامات',
            subtitle: 'حمامات فاخرة بتصاميم عصرية تجمع بين الأناقة والعملية',
            images: [
                'assets/image_services/Baths/IMG_0297.JPG',
                'assets/image_services/Baths/IMG_0298.JPG',
                'assets/image_services/Baths/IMG_0303.JPG',
                'assets/image_services/Baths/IMG_0304.JPG'
            ]
        },
        toilet: {
            title: 'دورات المياه',
            subtitle: 'تصاميم عملية وأنيقة مع استغلال أمثل للمساحة',
            images: [
                'assets/image_services/For a toilet (bathroom)/IMG_0315.JPG',
                'assets/image_services/For a toilet (bathroom)/IMG_0316.JPG',
                'assets/image_services/For a toilet (bathroom)/IMG_0319.JPG',
                'assets/image_services/For a toilet (bathroom)/IMG_0321.JPG'
            ]
        },
        halls: {
            title: 'تصميم الصالات',
            subtitle: 'صالات فسيحة وأنيقة مع ديكورات عصرية وإضاءة مدروسة',
            images: [
                'assets/image_services/Halls/IMG_0290.JPG',
                'assets/image_services/Halls/IMG_0291.JPG',
                'assets/image_services/Halls/IMG_0300.JPG',
                'assets/image_services/Halls/IMG_0309.JPG',
                'assets/image_services/Halls/IMG_0325.JPG',
                'assets/image_services/Halls/IMG_0326[1].JPG',
                'assets/image_services/Halls/IMG_0328.JPG',
                'assets/image_services/Halls/IMG_0329.JPG'
            ]
        },

        // ✅ قبل / بعد – نفس مسارات الصور اللي استخدمناها في services.php
        beforeafter: {
            title: 'معرض قبل / بعد',
            subtitle: 'تحويلات حقيقية قبل التنفيذ وبعده — شاهد الفرق في مشاريع قبل & بعد',
            images: [
                'assets/befor_after_images/1/IMG_1407.JPG',
                'assets/befor_after_images/1/IMG_1408.JPG',
                'assets/befor_after_images/2/IMG_1405.JPG',
                'assets/befor_after_images/2/IMG_1406.JPG',
                'assets/befor_after_images/3/IMG_1409.JPG',
                'assets/befor_after_images/3/IMG_1410.JPG'
            ]
        }
    };

    // =========================================================
    // 🔗 ربط عناصر الصفحة
    // =========================================================
    let currentService = null;
    let currentImageIndex = 0;
    let currentImages = [];

    const galleryModal   = document.getElementById('galleryModal');
    const modalTitle     = document.getElementById('modalTitle');
    const modalSubtitle  = document.getElementById('modalSubtitle');
    const galleryGrid    = document.getElementById('galleryGrid');
    const modalClose     = document.getElementById('modalClose');

    const lightbox       = document.getElementById('lightbox');
    const lightboxImage  = document.getElementById('lightboxImage');
    const lightboxCounter= document.getElementById('lightboxCounter');
    const lightboxClose  = document.getElementById('lightboxClose');
    const lightboxPrev   = document.getElementById('lightboxPrev');
    const lightboxNext   = document.getElementById('lightboxNext');

    // لو الصفحة ما فيها المعرض (احتياط)
    if (!galleryModal || !lightbox) return;

    const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

    function smoothAnimation(callback) {
        if ('requestAnimationFrame' in window) {
            return requestAnimationFrame(callback);
        }
        return setTimeout(callback, 16);
    }

    // =========================================================
    // 🔄 تهيئة عند تحميل الـ DOM
    // =========================================================
    document.addEventListener('DOMContentLoaded', () => {
        initServiceItems();
        initModalControls();
        initLightboxControls();
        initRevealAnimations();
    });

    // =========================================================
    // 🎫 ربط بطاقات الخدمات بالأزرار
    // =========================================================
    function initServiceItems() {
        const serviceItems = document.querySelectorAll('.service-item');

        serviceItems.forEach(item => {
            const serviceName = item.getAttribute('data-service');
            const viewBtn = item.querySelector('.view-gallery-btn');

            if (!serviceName) return;

            const openGallery = (e) => {
                e.preventDefault();
                e.stopPropagation();
                openServiceGallery(serviceName);
            };

            if (viewBtn) {
                viewBtn.addEventListener('click', openGallery);
            }

            item.addEventListener('click', (e) => {
                if (!e.target.closest('.view-gallery-btn')) {
                    openGallery(e);
                }
            });
        });
    }

    // =========================================================
    // 🖼️ فتح معرض خدمة معيّنة
    // =========================================================
    function openServiceGallery(serviceName) {
        const serviceItem = document.querySelector(`.service-item[data-service="${serviceName}"]`);
        if (!serviceItem) return;

        const baseData = servicesData[serviceName] || {};

        const htmlTitleEl = serviceItem.querySelector('.service-title');
        const htmlDescEl  = serviceItem.querySelector('.service-description');

        const title = baseData.title || (htmlTitleEl ? htmlTitleEl.textContent.trim() : 'معرض الصور');
        const subtitle = baseData.subtitle || (htmlDescEl ? htmlDescEl.textContent.trim() : 'اضغط على الصورة لعرضها بالحجم الكامل');

        // نستخدم الصور من servicesData مباشرة
        const images = Array.isArray(baseData.images) ? baseData.images.slice() : [];

        if (!images || !images.length) {
            console.warn('لا توجد صور لهذه الخدمة:', serviceName);
            return;
        }

        currentService = serviceName;
        currentImages = images;

        modalTitle.textContent = title;
        modalSubtitle.textContent = subtitle;

        renderGallery(images);

        smoothAnimation(() => {
            galleryModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // =========================================================
    // 🧱 بناء شبكة الصور داخل المودال
    // =========================================================
    function renderGallery(images) {
        const fragment = document.createDocumentFragment();

        images.forEach((src, index) => {
            const item = document.createElement('div');
            item.className = 'gallery-item';
            item.dataset.index = index;

            const img = document.createElement('img');
            img.src = src;
            img.alt = `${modalTitle.textContent} - صورة ${index + 1}`;
            img.loading = 'lazy';
            img.decoding = 'async';

            const overlay = document.createElement('div');
            overlay.className = 'gallery-item-overlay';
            overlay.innerHTML = '<div class="gallery-item-icon">🔍</div>';

            item.appendChild(img);
            item.appendChild(overlay);

            item.addEventListener('click', () => openLightbox(index), { passive: true });

            fragment.appendChild(item);
        });

        galleryGrid.innerHTML = '';
        galleryGrid.appendChild(fragment);
    }

    // =========================================================
    // 🔍 Lightbox
    // =========================================================
    function openLightbox(index) {
        if (!currentImages.length) return;

        currentImageIndex = index;
        updateLightboxImage();

        smoothAnimation(() => {
            lightbox.classList.add('active');
        });
    }

    function updateLightboxImage() {
        const src = currentImages[currentImageIndex];
        if (!src) return;

        lightboxImage.style.opacity = '0';

        lightboxImage.onload = () => {
            smoothAnimation(() => {
                lightboxImage.style.opacity = '1';
            });
        };

        lightboxImage.src = src;
        lightboxImage.alt = `${modalTitle.textContent} - صورة ${currentImageIndex + 1}`;
        lightboxCounter.textContent = `${currentImageIndex + 1} / ${currentImages.length}`;
    }

    function closeModal() {
        galleryModal.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(() => {
            galleryGrid.innerHTML = '';
            currentService = null;
            currentImages = [];
        }, 300);
    }

    function closeLightbox() {
        lightbox.classList.remove('active');
    }

    function prevImage() {
        if (!currentImages.length) return;
        currentImageIndex = (currentImageIndex - 1 + currentImages.length) % currentImages.length;
        updateLightboxImage();
    }

    function nextImage() {
        if (!currentImages.length) return;
        currentImageIndex = (currentImageIndex + 1) % currentImages.length;
        updateLightboxImage();
    }

    // =========================================================
    // 🎛️ تحكمات المودال واللايت بوكس
    // =========================================================
    function initModalControls() {
        if (modalClose) {
            modalClose.addEventListener('click', closeModal);
        }

        galleryModal.addEventListener('click', (e) => {
            if (e.target === galleryModal || e.target.classList.contains('modal-overlay')) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (lightbox.classList.contains('active')) {
                    closeLightbox();
                } else if (galleryModal.classList.contains('active')) {
                    closeModal();
                }
            }
        });
    }

    function initLightboxControls() {
        if (lightboxClose) {
            lightboxClose.addEventListener('click', closeLightbox);
        }

        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox || e.target.classList.contains('lightbox-overlay')) {
                closeLightbox();
            }
        });

        if (lightboxPrev) {
            lightboxPrev.addEventListener('click', (e) => {
                e.stopPropagation();
                nextImage();
            });
        }

        if (lightboxNext) {
            lightboxNext.addEventListener('click', (e) => {
                e.stopPropagation();
                prevImage();
            });
        }

        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'ArrowRight') {
                prevImage();
            } else if (e.key === 'ArrowLeft') {
                nextImage();
            }
        });

        if (isTouch) {
            let startX = 0;
            let endX = 0;

            lightbox.addEventListener('touchstart', (e) => {
                startX = e.changedTouches[0].screenX;
            }, { passive: true });

            lightbox.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].screenX;
                const diff = startX - endX;
                const threshold = 50;

                if (Math.abs(diff) > threshold) {
                    if (diff > 0) {
                        nextImage();
                    } else {
                        prevImage();
                    }
                }
            }, { passive: true });
        }
    }

    // =========================================================
    // ✨ Reveal Animation بسيطة
    // =========================================================
    function initRevealAnimations() {
        const revealElements = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        revealElements.forEach(el => observer.observe(el));
    }

    // =========================================================
    // 🎬 Hero Slider + زر "استعرض الخدمات"
    // =========================================================
    function initHeroSlider() {
        const heroSlider = document.querySelector('.services-hero-slider');
        if (!heroSlider) return;

        const slides = heroSlider.querySelectorAll('.hero-slide');
        if (slides.length <= 1) return;

        let current = 0;

        function nextSlide() {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }

        setInterval(nextSlide, 5000);
    }

    function initScrollToServices() {
        const btn = document.querySelector('.scroll-to-services');
        const section = document.querySelector('.services-gallery-section');

        if (!btn || !section) return;

        btn.addEventListener('click', () => {
            section.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }

    window.addEventListener('load', () => {
        initHeroSlider();
        initScrollToServices();
    });
})();
