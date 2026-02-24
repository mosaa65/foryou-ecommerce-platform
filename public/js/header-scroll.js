// assets/js/header-scroll.js

document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.site-header');
  const navMobile = document.querySelector('.nav-mobile');
  const menuToggle = document.querySelector('.menu-toggle');

  // ضبط ارتفاع الهيدر في --header-height عشان قائمة الجوال توقف تحته بالضبط
  function setHeaderHeightVar() {
    if (!header) return;
    const h = header.getBoundingClientRect().height || 72;
    document.documentElement.style.setProperty('--header-height', h + 'px');
  }

  setHeaderHeightVar();
  window.addEventListener('resize', setHeaderHeightVar);
  window.addEventListener('orientationchange', setHeaderHeightVar);

  // لو العناصر موجودة، نفعل منطق القائمة
  if (menuToggle && navMobile) {
    menuToggle.setAttribute('aria-expanded', 'false');
    navMobile.setAttribute('aria-hidden', 'true');

    // فتح/إغلاق القائمة عند الضغط على زر الهامبرجر
    menuToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      const open = navMobile.classList.toggle('show');
      menuToggle.classList.toggle('active', open);
      menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      navMobile.setAttribute('aria-hidden', open ? 'false' : 'true');
      document.body.style.overflow = open ? 'hidden' : '';

      if (open) {
        setTimeout(() => {
          const firstLink = navMobile.querySelector('a');
          if (firstLink) firstLink.focus();
        }, 200);
      }
    });

    // إغلاق عند النقر خارج القائمة
    document.addEventListener('click', (ev) => {
      if (!navMobile.contains(ev.target) && !menuToggle.contains(ev.target)) {
        if (!navMobile.classList.contains('show')) return;
        navMobile.classList.remove('show');
        menuToggle.classList.remove('active');
        menuToggle.setAttribute('aria-expanded', 'false');
        navMobile.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
    }, { capture: true });

    // إغلاق بالـ Escape
    document.addEventListener('keydown', (ev) => {
      if (ev.key === 'Escape' && navMobile.classList.contains('show')) {
        navMobile.classList.remove('show');
        menuToggle.classList.remove('active');
        menuToggle.setAttribute('aria-expanded', 'false');
        navMobile.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
    });

    // إغلاق لما المستخدم يختار أي رابط من القائمة
    navMobile.addEventListener('click', (ev) => {
      if (ev.target.tagName === 'A') {
        navMobile.classList.remove('show');
        menuToggle.classList.remove('active');
        menuToggle.setAttribute('aria-expanded', 'false');
        navMobile.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }
    });
  }

  // تأثير إضافة/حذف كلاس scrolled للهيدر عند التمرير
  document.addEventListener('scroll', () => {
    if (!header) return;
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });
});
