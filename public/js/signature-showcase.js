document.addEventListener("DOMContentLoaded", () => {
    const frames = document.querySelectorAll(".sig-frame");
    if (!frames.length) return;

    let index = 0;
    setInterval(() => {
        frames[index].classList.remove("active");
        index = (index + 1) % frames.length;
        frames[index].classList.add("active");
    }, 2600);
});
