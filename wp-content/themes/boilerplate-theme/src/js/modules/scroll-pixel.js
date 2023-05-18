
export default function() {

  const scrollPixel = document.getElementById(`scroll-pixel`); 
  const scrollPixelIO = new IntersectionObserver((entries) => {
    (!entries[0].isIntersecting) ? 
      document.body.classList.add(`is-scrolled`) : 
      document.body.classList.remove(`is-scrolled`);
  }, {
    threshold: 1
  });
  scrollPixelIO.observe(scrollPixel);   

}
