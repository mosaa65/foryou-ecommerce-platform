document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".project-card-strip");

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15 }
    );

    cards.forEach((card, index) => {
        card.style.transitionDelay = `${index * 0.04}s`;
        observer.observe(card);
    });
});