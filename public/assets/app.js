(() => {
  document.querySelectorAll('.review-card').forEach((card) => {
    // Image fallback: show the placeholder if the remote image fails to load.
    const img = card.querySelector('[data-review-image]');
    const placeholder = card.querySelector('[data-review-placeholder]');
    if (img && placeholder) {
      const opener = card.querySelector('[data-lightbox-open]');
      const fallback = () => {
        img.classList.add('hidden');
        placeholder.classList.remove('hidden');
        if (opener) opener.classList.add('hidden');
      };
      img.addEventListener('error', fallback);
      if (img.complete && img.naturalWidth === 0) fallback();
    }

    // "Mehr lesen": only offered when the text is actually clamped.
    const text = card.querySelector('[data-review-text]');
    const toggle = card.querySelector('[data-review-toggle]');
    if (!text || !toggle) return;
    const update = () => {
      const expanded = !text.classList.contains('line-clamp-5');
      const overflowing = text.scrollHeight > text.clientHeight + 1;
      // Keep the button while expanded so the user can collapse again.
      toggle.classList.toggle('hidden', !(expanded || overflowing));
    };
    update();
    // Re-check whenever layout changes (resize, web fonts finishing, etc.).
    if ('ResizeObserver' in window) new ResizeObserver(update).observe(text);
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(update);
    window.addEventListener('load', update);
    toggle.addEventListener('click', () => {
      const expanded = text.classList.toggle('line-clamp-5') === false;
      toggle.textContent = expanded ? 'Weniger anzeigen' : 'Mehr lesen';
      toggle.setAttribute('aria-expanded', String(expanded));
    });
  });

  // Lightbox: native <dialog> gives us Esc-to-close, focus trapping and focus restore.
  const dialog = document.getElementById('lightbox');
  if (!dialog || typeof dialog.showModal !== 'function') return;
  const img = dialog.querySelector('[data-lightbox-img]');
  const caption = dialog.querySelector('[data-lightbox-caption]');
  const prev = dialog.querySelector('[data-lightbox-prev]');
  const next = dialog.querySelector('[data-lightbox-next]');
  let images = [];
  let index = 0;

  const show = (i) => {
    index = (i + images.length) % images.length;
    img.src = images[index].url;
    img.alt = images[index].alt || '';
    caption.textContent = images.length > 1 ? `${index + 1} / ${images.length}` : (images[index].alt || '');
    const single = images.length < 2;
    prev.classList.toggle('invisible', single);
    next.classList.toggle('invisible', single);
  };

  document.querySelectorAll('[data-lightbox-open]').forEach((btn) => {
    btn.addEventListener('click', () => {
      try { images = JSON.parse(btn.dataset.images || '[]'); } catch { images = []; }
      if (!images.length) return;
      show(0);
      dialog.showModal();
    });
  });

  prev.addEventListener('click', () => show(index - 1));
  next.addEventListener('click', () => show(index + 1));
  dialog.querySelector('[data-lightbox-close]').addEventListener('click', () => dialog.close());
  // Click on the dark area (not the image or buttons) closes.
  dialog.addEventListener('click', (ev) => {
    if (ev.target === dialog || ev.target.hasAttribute('data-lightbox-backdrop')) dialog.close();
  });
  dialog.addEventListener('keydown', (ev) => {
    if (images.length < 2) return;
    if (ev.key === 'ArrowLeft') show(index - 1);
    if (ev.key === 'ArrowRight') show(index + 1);
  });
  dialog.addEventListener('close', () => { img.removeAttribute('src'); });

  // Touch: swipe left/right to navigate.
  let startX = null;
  img.addEventListener('touchstart', (ev) => { startX = ev.touches[0].clientX; }, { passive: true });
  img.addEventListener('touchend', (ev) => {
    if (startX === null || images.length < 2) return;
    const dx = ev.changedTouches[0].clientX - startX;
    if (Math.abs(dx) > 50) show(index + (dx < 0 ? 1 : -1));
    startX = null;
  });
})();
