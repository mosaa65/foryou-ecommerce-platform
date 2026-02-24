// assets/js/menu.js

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileNav = document.querySelector('.nav-mobile');

    if (!menuToggle || !mobileNav) return;

    menuToggle.addEventListener('click', () => {
        const isActive = menuToggle.classList.toggle('active');
        mobileNav.classList.toggle('show', isActive);
    });

    mobileNav.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            menuToggle.classList.remove('active');
            mobileNav.classList.remove('show');
        });
    });
});
