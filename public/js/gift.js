/* ============================================
   🎁 Gift Details Page - من أجلك للهدايا
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {
    initGiftThumbs();
});

/**
 * تبديل الصورة الرئيسية عند الضغط على الصور المصغرة
 */
function initGiftThumbs() {
    const mainImage = document.getElementById('giftMainImage');
    const thumbs = document.querySelectorAll('.gift-thumb');

    if (!mainImage || thumbs.length === 0) {
        return;
    }

    thumbs.forEach((thumb) => {
        thumb.addEventListener('click', () => {
            const src = thumb.getAttribute('data-full-src');
            if (!src) return;

            // تبديل كلاس active بين الثمبنيلز
            thumbs.forEach((t) => t.classList.remove('active'));
            thumb.classList.add('active');

            // أنيميشن خفيفة للصورة الرئيسية
            mainImage.style.opacity = '0';
            setTimeout(() => {
                mainImage.src = src;
                mainImage.onload = () => {
                    mainImage.style.opacity = '1';
                };
            }, 120);
        });
    });
}
