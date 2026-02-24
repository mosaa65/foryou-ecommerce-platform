// assets/js/reveal.js

document.addEventListener('DOMContentLoaded', () => {
    const reveals = document.querySelectorAll('.reveal');
    if (!reveals.length) return;

    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        reveals.forEach(el => obs.observe(el));
    } else {
        // متصفحات قديمة: نعرضها مباشرة
        reveals.forEach(el => el.classList.add('visible'));
    }
});
