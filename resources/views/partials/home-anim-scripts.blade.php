<script>
(function() {
  const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) return;

  const nodes = document.querySelectorAll('[data-anim="reveal"], [data-anim="reveal"] *');

  // We only want top-level elements that carry reveal classes.
  const revealEls = document.querySelectorAll('.reveal, .reveal-soft');
  if (!revealEls.length) return;

  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -10% 0px' });

  revealEls.forEach((el) => {
    // Optional delay for stagger
    const delay = el.getAttribute('data-anim-delay');
    if (delay) {
      const ms = parseFloat(delay) || 0;
      el.style.transitionDelay = ms + 'ms';
    }
    io.observe(el);
  });
})();
</script>

