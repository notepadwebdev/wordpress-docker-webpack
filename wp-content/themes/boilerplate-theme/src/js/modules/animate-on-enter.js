
export default function(el) {

  const rootMargin = (el.dataset.rootMargin) ? el.dataset.rootMargin : '0px 0px -5% 0px';
  
  // Intersection Observer.
  const intersectionObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.remove('animate-on-enter');
      }
    });
  }, {
    rootMargin: rootMargin,
    threshold: 0
  });

  intersectionObserver.observe(el);
  
}
