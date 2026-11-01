(() => {
  document.querySelectorAll('.review-card').forEach((card) => {
    // Image fallback: show the placeholder if the remote image fails to load.
    const img = card.querySelector('[data-review-image]');
    const placeholder = card.querySelector('[data-review-placeholder]');
    if (img && placeholder) {
      const opener = card.querySelector('[data-review-opener]');
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

  // Lightbox: GLightbox (CDN); anchors sharing a data-gallery form one gallery per review.
  if (typeof GLightbox === 'function') {
    GLightbox({ selector: '.glightbox', loop: true, touchNavigation: true, keyboardNavigation: true });
  }
})();
