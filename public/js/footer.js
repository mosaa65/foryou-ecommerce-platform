// assets/js/footer.js
// منطق خاص بالفوتر + الأزرار العائمة في موقع "قبل & بعد"

document.addEventListener('DOMContentLoaded', function () {
  // زر الرجوع للأعلى (يدعم أي ID مستعمل)
  const backToTop =
    document.getElementById('backToTop') ||
    document.querySelector('.top-btn');

  const scrollThreshold = 260;

  if (backToTop) {
    // حالة ابتدائية
    backToTop.style.transition = backToTop.style.transition || 'all .22s ease';
    backToTop.style.opacity = backToTop.style.opacity || '0';
    backToTop.style.transform =
      backToTop.style.transform || 'translateY(10px)';

    function toggleBackToTop() {
      if (window.scrollY > scrollThreshold) {
        backToTop.style.opacity = '1';
        backToTop.style.transform = 'translateY(0)';
      } else {
        backToTop.style.opacity = '0';
        backToTop.style.transform = 'translateY(10px)';
      }
    }

    toggleBackToTop();
    window.addEventListener('scroll', toggleBackToTop);

    backToTop.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // حركات بسيطة للأزرار العائمة (واتساب / سناب / عرض قبل وبعد)
  const floatButtons = document.querySelectorAll('.floating-buttons a');

  floatButtons.forEach((btn) => {
    // تأكيد وجود transition
    if (!btn.style.transition) {
      btn.style.transition = 'all .2s ease';
    }

    btn.addEventListener('mouseenter', () => {
      btn.style.transform = 'translateY(-3px)';
    });

    btn.addEventListener('mouseleave', () => {
      btn.style.transform = 'translateY(0)';
    });
  });
});
