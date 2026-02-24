document.addEventListener("DOMContentLoaded", () => {
    const heroSlider = document.querySelector('.hero.services-hero-slider');
    if (!heroSlider) return;

    const slides = heroSlider.querySelectorAll('.hero-slide');
    if (slides.length <= 1) return;

    let current = 0;

    // تأكد أن فيه سلايد Active واحد فقط
    slides.forEach((slide, index) => {
        if (slide.classList.contains('active')) {
            current = index;
        } else {
            slide.classList.remove('active');
        }
    });
    slides[current].classList.add('active');

    function nextSlide() {
        slides[current].classList.remove('active');
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
    }

    // تبديل كل 5 ثواني
    setInterval(nextSlide, 5000);
});
