import AnimateOnEnter from "./modules/animate-on-enter";

document.addEventListener("DOMContentLoaded", function(event) {
    
  console.log(`Let's do this, yeah?!`);
  
  /** 
   *       Animate on Enter.
   */
  const aoes = document.querySelectorAll('.animate-on-enter');
  [...aoes].forEach(aoe => new AnimateOnEnter(aoe));
  
    
  document.documentElement.classList.remove('no-js');
});
