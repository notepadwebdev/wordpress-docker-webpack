
export default function() {
  
  // DOM.
  const siteHeader = document.getElementById('site-header');
  const hamburger = siteHeader.querySelector('.hamburger'); 
  const flyout = siteHeader.querySelector('.site-header__flyout');

  const openNav = () => {
    hamburger.setAttribute('aria-expanded', 'true');
    flyout.classList.add('is-visible');
    document.documentElement.classList.add('flyout-is-visible');
  }

  const closeNav = () => {
    hamburger.setAttribute('aria-expanded', 'false');
    flyout.classList.remove('is-visible');
    document.documentElement.classList.remove('flyout-is-visible');
  }
  
  const toggleMenu = () => { 
    var menuOpen = flyout.classList.contains('is-visible');
    (menuOpen) ? closeNav() : openNav();
  };

  const showSubNav = item => {
    item.setAttribute('aria-expanded', `true`);
  }

  const hideSubNav = item => {
    item.setAttribute('aria-expanded', `false`);
  }

  const toggleSubNav = item => {
    if (item.getAttribute('aria-expanded') == `true`) {
      hideSubNav(item);
    } else {
      showSubNav(item)
    }
  }

  
  // Resize Observer.
  const resizeObserver = new ResizeObserver(entries => {});
  resizeObserver.observe(siteHeader);

 
  /**
   *  Event Listeners.
   */
  hamburger.addEventListener('click', toggleMenu);

}
