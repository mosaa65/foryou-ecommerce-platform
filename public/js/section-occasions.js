document.addEventListener("DOMContentLoaded", () => {
    const section = document.querySelector('.occasion-section');
    if (!section) return;

    const cards = Array.from(section.querySelectorAll('.occasion-card'));
    if (!cards.length) return;

    const observer = new IntersectionObserver(
        entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;

                // عند ظهور السكشن، نضيف كلاس is-visible مع تأخير بسيط لكل كرت
                cards.forEach(card => {
                    const index = parseInt(card.getAttribute('data-index') || '0', 10);
                    const delay = 80 * index;
                    setTimeout(() => {
                        card.classList.add('is-visible');
                    }, delay);
                });

                observer.disconnect();
            });
        },
        { threshold: 0.25 }
    );

    observer.observe(section);
});
