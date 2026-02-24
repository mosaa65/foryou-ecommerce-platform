// assets/js/before-after.js
document.addEventListener('DOMContentLoaded', () => {
  const cards = Array.from(document.querySelectorAll('[data-ba-card]'));
  if (!cards.length) return;

  /* ===== دوال مساعدة لحالة الكرت ===== */
  const applyCardState = (card, state) => {
    const img = card.querySelector('[data-ba-img]');
    const labelBefore = card.querySelector('[data-ba-label-before]');
    const labelAfter = card.querySelector('[data-ba-label-after]');
    if (!img || !labelBefore || !labelAfter) return;

    const beforeSrc = img.dataset.before;
    const afterSrc = img.dataset.after;
    const baseAlt = img.dataset.altBase || '';

    const nextSrc = state === 'before' ? beforeSrc : afterSrc;
    if (nextSrc) img.src = nextSrc;

    card.dataset.state = state;
    img.alt = baseAlt
      ? `${baseAlt} - ${state === 'before' ? 'قبل' : 'بعد'}`
      : img.alt;

    labelBefore.classList.toggle('is-active', state === 'before');
    labelAfter.classList.toggle('is-active', state === 'after');
  };

  const toggleCardState = (card) => {
    const current = card.dataset.state === 'before' ? 'before' : 'after';
    const next = current === 'before' ? 'after' : 'before';
    applyCardState(card, next);
  };

  // تهيئة أولية (ضبط الحالة على "بعد" لكل الكروت)
  cards.forEach(card => applyCardState(card, 'after'));

  /* ===== تبديل تلقائي كل 5 ثواني ===== */
  setInterval(() => {
    cards.forEach(card => {
      toggleCardState(card);
    });
  }, 5000);

  /* ===== زر ⇆ داخل الكرت ===== */
  cards.forEach(card => {
    const btn = card.querySelector('[data-ba-btn]');
    if (!btn) return;

    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleCardState(card);
    });
  });

  /* ===== لايت بوكس ===== */
  const lightbox = document.querySelector('[data-ba-lightbox]');
  const lbImg = lightbox?.querySelector('[data-ba-lb-img]');
  const lbCaption = lightbox?.querySelector('[data-ba-lb-caption]');
  const lbLabelBefore = lightbox?.querySelector('[data-ba-lb-before]');
  const lbLabelAfter = lightbox?.querySelector('[data-ba-lb-after]');
  const lbPrev = lightbox?.querySelector('[data-ba-lb-prev]');
  const lbNext = lightbox?.querySelector('[data-ba-lb-next]');
  const lbCloseEls = lightbox?.querySelectorAll('[data-ba-close]');
  const lbIndicator = lightbox?.querySelector('[data-ba-lb-indicator]');

  let currentCard = null;
  let lbState = 'after';

  const applyLightboxState = () => {
    if (!currentCard || !lbImg || !lbLabelBefore || !lbLabelAfter) return;
    const img = currentCard.querySelector('[data-ba-img]');
    if (!img) return;

    const beforeSrc = img.dataset.before;
    const afterSrc = img.dataset.after;
    const baseAlt = img.dataset.altBase || '';
    const title = currentCard.dataset.title || '';
    const text = currentCard.dataset.text || '';

    const src = lbState === 'before' ? beforeSrc : afterSrc;
    if (src) lbImg.src = src;
    lbImg.alt = baseAlt
      ? `${baseAlt} - ${lbState === 'before' ? 'قبل' : 'بعد'}`
      : '';

    // تحديث الليبل داخل اللايت بوكس
    lbLabelBefore.classList.toggle('is-active', lbState === 'before');
    lbLabelAfter.classList.toggle('is-active', lbState === 'after');

    if (lbCaption) {
      const stateText = lbState === 'before' ? 'الصورة قبل التنفيذ' : 'الصورة بعد التنفيذ';
      lbCaption.textContent = `${title ? title + ' – ' : ''}${stateText}. ${text}`;
    }
  };

  const openLightbox = (card) => {
    if (!lightbox) return;
    currentCard = card;
    // افتح اللايت بوكس على نفس حالة الكرت
    lbState = card.dataset.state === 'before' ? 'before' : 'after';
    applyLightboxState();
    lightbox.removeAttribute('hidden');
  };

  const closeLightbox = () => {
    if (!lightbox) return;
    lightbox.setAttribute('hidden', '');
    // رجّع حالة اللايت بوكس إلى الكرت
    if (currentCard) {
      applyCardState(currentCard, lbState);
    }
    currentCard = null;
  };

  // فتح اللايت بوكس عند الضغط على الصورة
  cards.forEach(card => {
    const openArea = card.querySelector('[data-ba-open]');
    if (!openArea) return;

    openArea.addEventListener('click', () => {
      openLightbox(card);
    });
  });

  // أزرار قبل/بعد داخل اللايت بوكس
  lbPrev?.addEventListener('click', () => {
    lbState = 'before';
    applyLightboxState();
  });

  lbNext?.addEventListener('click', () => {
    lbState = 'after';
    applyLightboxState();
  });

  // إغلاق اللايت بوكس
  lbCloseEls?.forEach(el => {
    el.addEventListener('click', closeLightbox);
  });

  // إغلاق بالضغط على ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && lightbox && !lightbox.hasAttribute('hidden')) {
      closeLightbox();
    }
  });
});
