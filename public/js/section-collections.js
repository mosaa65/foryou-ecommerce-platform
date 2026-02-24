document.addEventListener("DOMContentLoaded", () => {
    const section = document.querySelector('.collections-section');
    if (!section) return;

    const track = section.querySelector('.collections-track');
    const cards = Array.from(section.querySelectorAll('.collection-card'));
    const btnPrev = section.querySelector('.collections-nav--prev');
    const btnNext = section.querySelector('.collections-nav--next');

    if (!track || !cards.length) return;

    // 1) ظهور البطاقات بتسلسل
    const revealObserver = new IntersectionObserver(
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

                revealObserver.disconnect();
            });
        },
        { threshold: 0.25 }
    );
    revealObserver.observe(section);

    // 2) تحريك السلايدر بالأزرار
    function updateNavState() {
        const maxScrollLeft = track.scrollWidth - track.clientWidth;
        const current = track.scrollLeft;

        if (!btnPrev || !btnNext) return;

        // هامش بسيط عشان ما يتعب المستخدم في البكسلات
        const epsilon = 5;

        if (current <= epsilon) {
            btnPrev.classList.add('is-disabled');
        } else {
            btnPrev.classList.remove('is-disabled');
        }

        if (current >= maxScrollLeft - epsilon) {
            btnNext.classList.add('is-disabled');
        } else {
            btnNext.classList.remove('is-disabled');
        }
    }

    function scrollByCard(direction) {
        const card = cards[0];
        if (!card) return;

        const cardWidth = card.getBoundingClientRect().width;
        const gap = 14;
        const delta = (cardWidth + gap) * direction;

        track.scrollBy({
            left: delta * -1, // لإتجاه RTL
            behavior: 'smooth'
        });

        setTimeout(updateNavState, 400);
    }

    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            if (btnPrev.classList.contains('is-disabled')) return;
            scrollByCard(1);
        });
    }

    if (btnNext) {
        btnNext.addEventListener('click', () => {
            if (btnNext.classList.contains('is-disabled')) return;
            scrollByCard(-1);
        });
    }

    track.addEventListener('scroll', () => {
        updateNavState();
    });

    // أول مرة
    updateNavState();
});
