document.addEventListener("DOMContentLoaded", () => {
    const cards = Array.from(document.querySelectorAll(".villa-card"));

    // مصفوفة لتخزين بيانات كل كرت: الصور + المؤشر
    const cardsData = [];

    /* ===========================
       1) سلايدر الصور لكل كرت
       =========================== */
    cards.forEach((card) => {
        const imgBox = card.querySelector(".villa-img");
        const btnPrev = card.querySelector(".villa-img-prev");
        const btnNext = card.querySelector(".villa-img-next");

        if (!imgBox) return;

        let list = [];
        try {
            list = JSON.parse(card.dataset.images || "[]");
        } catch (e) {
            list = [];
        }

        if (!list.length) return;

        const dataEntry = {
            card,
            imgBox,
            images: list,
            current: 0,
        };
        cardsData.push(dataEntry);

        function setImage() {
            imgBox.style.backgroundImage = `url('${dataEntry.images[dataEntry.current]}')`;
        }

        function goTo(index) {
            const len = dataEntry.images.length;
            if (!len) return;
            dataEntry.current = (index + len) % len;
            imgBox.classList.add("changing");
            setTimeout(() => {
                setImage();
                imgBox.classList.remove("changing");
            }, 160);
        }

        // أول صورة
        setImage();

        // أزرار يمين/يسار لكل كرت
        if (btnPrev) {
            btnPrev.addEventListener("click", (e) => {
                e.stopPropagation();
                goTo(dataEntry.current - 1);
            });
        }

        if (btnNext) {
            btnNext.addEventListener("click", (e) => {
                e.stopPropagation();
                goTo(dataEntry.current + 1);
            });
        }

        // عند الضغط على البطاقة نفسها → افتح اللايت بوكس
        card.addEventListener("click", () => {
            openLightbox(
                dataEntry.images,
                card.querySelector(".villa-label")?.textContent || "",
                dataEntry.current
            );
        });
    });

    // سلايدر تلقائي موحّد لكل الكروت (كلهم يتغيّروا معاً)
    if (cardsData.length) {
        setInterval(() => {
            cardsData.forEach((entry) => {
                const len = entry.images.length;
                if (!len) return;
                entry.current = (entry.current + 1) % len;
                entry.imgBox.classList.add("changing");
                setTimeout(() => {
                    entry.imgBox.style.backgroundImage =
                        `url('${entry.images[entry.current]}')`;
                    entry.imgBox.classList.remove("changing");
                }, 160);
            });
        }, 7000); // 7 ثواني
    }

    /* ===========================
       2) عرض 4 كروت فقط (يمين/يسار)
       =========================== */
    const perPage = 4;
    let page = 0;

    function renderPage() {
        const cardsLocal = Array.from(document.querySelectorAll(".villa-card"));
        cardsLocal.forEach((card, i) => {
            card.style.display =
                i >= page * perPage && i < (page + 1) * perPage
                    ? "block"
                    : "none";
        });
    }

    const nextBtn = document.getElementById("nextCards");
    const prevBtn = document.getElementById("prevCards");

    if (nextBtn && prevBtn) {
        const totalPages = Math.ceil(cards.length / perPage) || 1;

        nextBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            page = (page + 1) % totalPages;
            renderPage();
        });

        prevBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            page = (page - 1 + totalPages) % totalPages;
            renderPage();
        });
    }

    renderPage();

    /* ===========================
       3) لايت بوكس لعرض صور البطاقة
       =========================== */
    const lightbox = document.getElementById("villaLightbox");
    const lbImage = document.getElementById("villaLightboxImage");
    const lbTitle = document.getElementById("villaLightboxTitle");
    const lbCounter = document.getElementById("villaLightboxCounter");
    const lbClose = lightbox?.querySelector(".villa-lightbox-close");
    const lbOverlay = lightbox?.querySelector(".villa-lightbox-overlay");
    const lbPrev = lightbox?.querySelector(".villa-lightbox-prev");
    const lbNext = lightbox?.querySelector(".villa-lightbox-next");

    let lbImages = [];
    let lbIndex = 0;

    function updateLightbox() {
        if (!lbImages.length) return;
        lbImage.src = lbImages[lbIndex];
        lbCounter.textContent = `${lbIndex + 1} / ${lbImages.length}`;
    }

    function openLightbox(images, title, startIndex = 0) {
        if (!lightbox || !images || !images.length) return;
        lbImages = images;
        lbIndex = startIndex || 0;
        lbTitle.textContent = title;
        updateLightbox();
        lightbox.classList.add("visible");
        document.body.style.overflow = "hidden";
    }

    function closeLightbox() {
        if (!lightbox) return;
        lightbox.classList.remove("visible");
        document.body.style.overflow = "";
        lbImages = [];
        lbIndex = 0;
    }

    lbClose?.addEventListener("click", (e) => {
        e.stopPropagation();
        closeLightbox();
    });
    lbOverlay?.addEventListener("click", closeLightbox);

    lbPrev?.addEventListener("click", (e) => {
        e.stopPropagation();
        if (!lbImages.length) return;
        lbIndex = (lbIndex - 1 + lbImages.length) % lbImages.length;
        updateLightbox();
    });

    lbNext?.addEventListener("click", (e) => {
        e.stopPropagation();
        if (!lbImages.length) return;
        lbIndex = (lbIndex + 1) % lbImages.length;
        updateLightbox();
    });

    document.addEventListener("keydown", (e) => {
        if (!lightbox || !lightbox.classList.contains("visible")) return;
        if (e.key === "Escape") closeLightbox();
        if (e.key === "ArrowLeft") lbPrev?.click();
        if (e.key === "ArrowRight") lbNext?.click();
    });
});
