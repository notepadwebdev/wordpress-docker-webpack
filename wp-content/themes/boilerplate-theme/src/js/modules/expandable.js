export default function(el) {

  const toggle = el.querySelector('.expandable__button');
  const content = el.querySelector('.expandable__content');
  
  const open = () => {
    toggle.setAttribute('aria-expanded', 'true');
  }
  
  const close = () => {
    toggle.setAttribute('aria-expanded', 'false');
  }
  
  toggle.addEventListener('click', e => {
    if (toggle.getAttribute('aria-expanded') == 'true') {
      close();
    } else {
      open();
    }
  });

}