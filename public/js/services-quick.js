document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".services-quick .service-card");

    // أنيميشن ظهور البطاقات
    cards.forEach((card, i) => {
        card.style.transitionDelay = `${i * 0.08}s`;
    });

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

    cards.forEach((card) => observer.observe(card));

    // سلايدر الصور باستخدام data-images بنفس أسلوب القسم الثاني (background-image واحد)
    cards.forEach((card) => {
        const attr = card.getAttribute("data-images");
        if (!attr) return;

        const urls = attr
            .split(",")
            .map((s) => s.trim())
            .filter(Boolean);

        if (!urls.length) return;

        const slider = card.querySelector(".service-image-slider");
        const slide = slider?.querySelector(".service-slide");
        const dotsContainer = slider?.querySelector(".slider-dots");
        const prevBtn = slider?.querySelector(".slider-nav.prev");
        const nextBtn = slider?.querySelector(".slider-nav.next");

        if (!slider || !slide || !dotsContainer || !prevBtn || !nextBtn) return;

        // إنشاء النقاط
        urls.forEach((_, index) => {
            const dot = document.createElement("button");
            if (index === 0) dot.classList.add("is-active");
            dotsContainer.appendChild(dot);
        });

        const dots = Array.from(dotsContainer.querySelectorAll("button"));
        let current = 0;

        const show = (index) => {
            if (!urls[index]) return;
            current = (index + urls.length) % urls.length;

            slide.style.opacity = 0;
            slide.style.transform = "scale(1.02)";

            setTimeout(() => {
                slide.style.backgroundImage = `url('${urls[current]}')`;
                slide.style.opacity = 1;
                slide.style.transform = "scale(1)";
            }, 120);

            dots.forEach((d, i) => {
                d.classList.toggle("is-active", i === current);
            });
        };

        // أزرار
        nextBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            show(current + 1);
        });

        prevBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            show(current - 1);
        });

        // النقاط
        dots.forEach((dot, index) => {
            dot.addEventListener("click", (e) => {
                e.stopPropagation();
                show(index);
            });
        });

        // عرض أول صورة
        show(0);
    });
});
