document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".pillar-card");

    // فقط للتأكد في الكونسول
    console.log("عدد كروت الركائز:", cards.length);

    // إعداد أنيميشن الظهور
    cards.forEach((card, index) => {
        card.style.transition = "all 0.55s cubic-bezier(.24,.82,.22,1)";
        card.style.transitionDelay = `${index * 0.07}s`;
    });

    if ("IntersectionObserver" in window && cards.length) {
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
    } else {
        // لو المتصفح لا يدعم الـ Observer أو ما في كروت
        cards.forEach((card) => card.classList.add("is-visible"));
    }

    // Fallback إضافي: بعد ثانية نضمن أن كل الكروت ظاهرة
    setTimeout(() => {
        cards.forEach((card) => card.classList.add("is-visible"));
    }, 1000);

    // تدوير الصور داخل بطاقات الركائز باستخدام data-images
    const imageCards = document.querySelectorAll(".pillar-card[data-images]");

    imageCards.forEach((card) => {
        const imagesAttr = card.getAttribute("data-images");
        if (!imagesAttr) return;

        const urls = imagesAttr
            .split(",")
            .map((src) => src.trim())
            .filter((src) => src.length > 0);

        console.log("صور البطاقة:", urls);

        if (!urls.length) return;

        const mediaImg = card.querySelector(".pillar-media-img");
        if (!mediaImg) return;

        let currentIndex = 0;

        // أول صورة
        mediaImg.style.backgroundSize = "cover";
        mediaImg.style.backgroundPosition = "center";
        mediaImg.style.backgroundImage = `url('${urls[currentIndex]}')`;
        mediaImg.style.opacity = "1";

        if (urls.length === 1) return;

        setInterval(() => {
            currentIndex = (currentIndex + 1) % urls.length;
            mediaImg.style.opacity = "0";

            setTimeout(() => {
                mediaImg.style.backgroundImage = `url('${urls[currentIndex]}')`;
                mediaImg.style.opacity = "1";
            }, 280);
        }, 2600);
    });
});
