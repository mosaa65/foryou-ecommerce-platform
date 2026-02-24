document.addEventListener("DOMContentLoaded", () => {
    const section = document.querySelector('.premium-section');
    if (!section) return;

    const cards = Array.from(section.querySelectorAll('.premium-card'));
    if (!cards.length) return;

    // 1) إظهار الكروت بتسلسل عند دخول السكشن
    const observer = new IntersectionObserver(
        entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;

                cards.forEach(card => {
                    const index = parseInt(card.getAttribute('data-index') || '0', 10);
                    const delay = 90 * index;
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

    // 2) تأثير إمالة خفيف عند تحريك الماوس فوق الكرت (على الأجهزة المكتبية فقط)
    if (window.matchMedia("(pointer: fine)").matches) {
        cards.forEach(card => {
            const inner = card.querySelector('.premium-card-inner');
            if (!inner) return;

            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;   // داخل الكرت
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = ((y - centerY) / centerY) * -3; // زاوية صغيرة
                const rotateY = ((x - centerX) / centerX) * 3;

                inner.style.transform = `
                    perspective(700px)
                    rotateX(${rotateX}deg)
                    rotateY(${rotateY}deg)
                    translateZ(4px)
                `;
            });

            card.addEventListener('mouseleave', () => {
                inner.style.transform = "perspective(700px) rotateX(0deg) rotateY(0deg) translateZ(0)";
            });
        });
    }
});
