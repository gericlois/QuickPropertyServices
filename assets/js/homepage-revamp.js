/* ============================================================
   Homepage Revamp - Interactive Components
   Trust Cards hover/click + How It Works step switching
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

  // --- Trust Cards: Expand on hover (desktop) / tap (mobile) ---
  const trustCards = document.querySelectorAll('.trust-card');
  if (trustCards.length) {
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

    if (isTouchDevice) {
      trustCards.forEach(function (card) {
        card.addEventListener('click', function () {
          const wasActive = card.classList.contains('active');
          trustCards.forEach(function (c) { c.classList.remove('active'); });
          if (!wasActive) {
            card.classList.add('active');
          }
        });
      });
    } else {
      trustCards.forEach(function (card) {
        card.addEventListener('mouseenter', function () {
          trustCards.forEach(function (c) { c.classList.remove('active'); });
          card.classList.add('active');
        });
      });
    }
  }

  // --- How It Works: Step switching ---
  const stepItems = document.querySelectorAll('.step-item');
  const stepImages = document.querySelectorAll('.step-image');

  if (stepItems.length && stepImages.length) {
    stepItems.forEach(function (item) {
      item.addEventListener('click', function () {
        var step = item.getAttribute('data-step');

        stepItems.forEach(function (s) { s.classList.remove('active'); });
        item.classList.add('active');

        stepImages.forEach(function (img) { img.classList.remove('active'); });
        var target = document.querySelector('.step-image[data-step="' + step + '"]');
        if (target) target.classList.add('active');
      });
    });
  }

});
