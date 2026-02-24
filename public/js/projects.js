/* =========================================================
   🏗️ صفحة المنتجات - Products Page
   ⚡ محسّن للأداء - Performance Optimized
   ========================================================= */

// =========================================================
// 🎯 Utility Functions
// =========================================================

function debounce(func, wait = 100) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function smoothAnimation(callback) {
    if ('requestAnimationFrame' in window) {
        return requestAnimationFrame(callback);
    }
    return setTimeout(callback, 16);
}

// =========================================================
// 📊 المتغيرات العامة
// =========================================================

let currentFilter = 'all';
let currentProject = null;
let currentImages = [];
let currentImageIndex = 0;

// =========================================================
// 📱 تحسينات الهواتف المحمولة
// =========================================================

const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

function optimizeTouchInteractions() {
    if (!isTouch) return;

    document.body.classList.add('touch-device');

    if ('scrollBehavior' in document.documentElement.style) {
        document.documentElement.style.scrollBehavior = 'smooth';
    }

    let lastTouchEnd = 0;
    document.addEventListener('touchend', (e) => {
        const now = Date.now();
        if (now - lastTouchEnd <= 300) {
            e.preventDefault();
        }
        lastTouchEnd = now;
    }, false);

    console.log('✅ تم تحسين التفاعلات اللمسية');
}

// =========================================================
// 🚀 التهيئة
// =========================================================

document.addEventListener('DOMContentLoaded', () => {
    console.log(`📱 نوع الجهاز: ${isMobile ? 'موبايل' : 'كمبيوتر'}`);
    console.log(`👆 دعم اللمس: ${isTouch ? 'نعم' : 'لا'}`);

    if (isMobile || isTouch) {
        optimizeTouchInteractions();
    }

    initFilters();
    initProjectCards();
    initLightboxControls();
    initRevealAnimations();
});

// =========================================================
// 🎛️ Filters
// =========================================================

function initFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    const projectsGrid = document.getElementById('projectsGrid');
    const projectsEmpty = document.getElementById('projectsEmpty');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.getAttribute('data-filter');

            // تحديث الأزرار
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            currentFilter = filter;

            // تصفية المنتجات
            let visibleCount = 0;

            projectCards.forEach(card => {
                const category = card.getAttribute('data-category');
                const shouldShow = filter === 'all' || category === filter;

                if (shouldShow) {
                    card.classList.remove('hidden');
                    visibleCount++;

                    smoothAnimation(() => {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';

                        setTimeout(() => {
                            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        }, 50);
                    });
                } else {
                    card.classList.add('hidden');
                }
            });

            // إظهار/إخفاء Empty State
            if (visibleCount === 0) {
                projectsEmpty.style.display = 'block';
                projectsGrid.style.display = 'none';
            } else {
                projectsEmpty.style.display = 'none';
                projectsGrid.style.display = 'grid';
            }
        });
    });
}

// =========================================================
// 🖼️ Project Cards - النقر يفتح الصورة مباشرة
// =========================================================

function initProjectCards() {
    const projectCards = document.querySelectorAll('.project-card');

    projectCards.forEach(card => {
        const viewBtn = card.querySelector('.view-project-btn');
        const projectData = card.querySelector('.project-data');
        const mainImage = card.querySelector('.project-main-image');

        if (!projectData) return;

        const project = JSON.parse(projectData.textContent);

        // عند النقر على زر عرض التفاصيل - يفتح الصورة مباشرة
        if (viewBtn) {
            viewBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                openImageDirectly(project);
            });
        }

        // عند النقر على الصورة - يفتح الصورة مباشرة
        if (mainImage) {
            mainImage.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                openImageDirectly(project);
            });
        }

        // عند النقر على البطاقة كلها - يفتح الصورة مباشرة
        card.addEventListener('click', (e) => {
            if (!e.target.closest('.view-project-btn') && !e.target.closest('.project-main-image')) {
                openImageDirectly(project);
            }
        });
    });
}

// فتح الصورة مباشرة في Lightbox
function openImageDirectly(project) {
    currentProject = project;
    currentImages = project.images || [];

    if (currentImages.length === 0) {
        // إذا لا توجد صور، استخدم صورة المنتج الرئيسية
        if (project.image_path) {
            currentImages = [project.image_path];
        } else {
            console.log('لا توجد صور متاحة');
            return;
        }
    }

    currentImageIndex = 0;
    openLightbox(0);
}

// =========================================================
// 🔍 Image Lightbox
// =========================================================

function openLightbox(imageIndex) {
    const imageLightbox = document.getElementById('imageLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCounter = document.getElementById('lightboxCounter');

    if (!imageLightbox || !lightboxImage) return;

    currentImageIndex = imageIndex;

    const imageSrc = currentImages[currentImageIndex];
    lightboxImage.src = imageSrc;
    lightboxImage.alt = currentProject ? `${currentProject.title} - صورة ${currentImageIndex + 1}` : 'صورة';

    if (lightboxCounter) {
        lightboxCounter.textContent = `${currentImageIndex + 1} / ${currentImages.length}`;
    }

    smoothAnimation(() => {
        imageLightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
}

function closeLightbox() {
    const imageLightbox = document.getElementById('imageLightbox');
    if (!imageLightbox) return;

    imageLightbox.classList.remove('active');
    document.body.style.overflow = '';
}

function updateLightboxImage() {
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCounter = document.getElementById('lightboxCounter');

    if (currentImages.length === 0 || !lightboxImage) return;

    const imageSrc = currentImages[currentImageIndex];
    lightboxImage.style.opacity = '0';

    smoothAnimation(() => {
        lightboxImage.src = imageSrc;
        lightboxImage.alt = currentProject ? `${currentProject.title} - صورة ${currentImageIndex + 1}` : 'صورة';

        if (lightboxCounter) {
            lightboxCounter.textContent = `${currentImageIndex + 1} / ${currentImages.length}`;
        }

        lightboxImage.onload = () => {
            smoothAnimation(() => {
                lightboxImage.style.opacity = '1';
            });
        };
    });
}

function prevImage() {
    smoothAnimation(() => {
        currentImageIndex = (currentImageIndex - 1 + currentImages.length) % currentImages.length;
        updateLightboxImage();
    });
}

function nextImage() {
    smoothAnimation(() => {
        currentImageIndex = (currentImageIndex + 1) % currentImages.length;
        updateLightboxImage();
    });
}

// =========================================================
// 🎮 Lightbox Controls
// =========================================================

function initLightboxControls() {
    const imageLightbox = document.getElementById('imageLightbox');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');

    if (!imageLightbox) return;

    // إغلاق Lightbox
    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }

    imageLightbox.addEventListener('click', (e) => {
        if (e.target === imageLightbox || e.target.classList.contains('lightbox-overlay')) {
            closeLightbox();
        }
    });

    // أزرار التنقل
    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', (e) => {
            e.stopPropagation();
            nextImage(); // الصورة التالية (في العربية - RTL)
        });
    }

    if (lightboxNext) {
        lightboxNext.addEventListener('click', (e) => {
            e.stopPropagation();
            prevImage(); // الصورة السابقة (في العربية - RTL)
        });
    }

    // إغلاق بزر Escape والتنقل بالأسهم
    document.addEventListener('keydown', (e) => {
        if (!imageLightbox.classList.contains('active')) return;

        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowRight') {
            prevImage();
        } else if (e.key === 'ArrowLeft') {
            nextImage();
        }
    });

    // التنقل بالسوايب على الموبايل
    let touchStartX = 0;
    let touchEndX = 0;

    imageLightbox.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    });

    imageLightbox.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;

        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                nextImage();
            } else {
                prevImage();
            }
        }
    });
}

// =========================================================
// ✨ Reveal Animations
// =========================================================

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

console.log('✅ صفحة المنتجات جاهزة!');
