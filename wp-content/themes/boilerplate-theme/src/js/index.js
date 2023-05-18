import AnimateOnEnter from "./modules/animate-on-enter";

document.addEventListener("DOMContentLoaded", function(event) {
    
  console.log(`Let's do this, yeah?!`);

  // new ScrollPixel();
  
  /** 
   *       Animate on Enter.
   */
  const aoes = document.querySelectorAll('.animate-on-enter');
  [...aoes].forEach(aoe => new AnimateOnEnter(aoe));

  /** 
   *       Share Links.
   */
  const shareLinks = document.querySelectorAll('.share');
  [...shareLinks].forEach(shareLink => new Share(shareLink));
  
    
  document.documentElement.classList.remove('no-js');
});
