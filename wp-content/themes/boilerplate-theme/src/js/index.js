import AnimateOnEnter from "./modules/animate-on-enter";
import Expandable from "./modules/expandable";
import ScrollPixel from "./modules/scroll-pixel";
import Share from './modules/share';
import SiteHeader from './modules/site-header';

document.addEventListener("DOMContentLoaded", function(event) {
    
  console.log(`Let's do this, yeah?!`);

  // new ScrollPixel();
  new SiteHeader();

  /** 
   *       Expandables.
   */
  const expandables = document.querySelectorAll('.expandable');
  [...expandables].forEach(expandable => new Expandable(expandable)); 
  
  /** 
   *       Animate on Enter.
   */
  // const aoes = document.querySelectorAll('.animate-on-enter');
  // [...aoes].forEach(aoe => new AnimateOnEnter(aoe));

  /** 
   *       Share Links.
   */
  // const shareLinks = document.querySelectorAll('.share');
  // [...shareLinks].forEach(shareLink => new Share(shareLink));
  
    
  document.documentElement.classList.remove('no-js');
});
