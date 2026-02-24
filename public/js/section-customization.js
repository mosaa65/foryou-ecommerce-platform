document.addEventListener("DOMContentLoaded", () => {
    const section = document.querySelector('.custom-section');
    if (!section) return;

    const cards = Array.from(section.querySelectorAll('.custom-card'));
    if (!cards.length) return;

    // 1) إظهار الكروت بتسلسل عند دخول السكشن
    const observer = new IntersectionObserver(
        entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;

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

    // 2) لمعة صغيرة على الدبوس عند المرور بالفأرة (أجهزة مكتبية فقط)
    if (window.matchMedia("(pointer: fine)").matches) {
        cards.forEach(card => {
            const pin = card.querySelector('.custom-card-pin');
            if (!pin) return;

            card.addEventListener('mouseenter', () => {
                pin.style.boxShadow =
                    "0 0 0 2px rgba(15,23,42,0.9), 0 0 12px rgba(191,219,254,0.9)";
            });

            card.addEventListener('mouseleave', () => {
                pin.style.boxShadow =
                    "0 0 0 2px rgba(15,23,42,0.9), 0 4px 8px rgba(15,23,42,0.9)";
            });
        });
    }
});
