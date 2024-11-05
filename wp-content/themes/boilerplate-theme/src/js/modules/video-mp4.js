(function ( $ ) {
  
  $.fn.videoMp4 = function(options) { 

    // DOM.
    const el = this.get(0);     
    if (!el) {
      return;
    }
    
    const videoEl = el.closest('.video');
    const container = videoEl.querySelector('.video__container');
    const poster = videoEl.querySelector('.video__poster');

    const onClick = e => {
      poster?.remove();

      el.play();
    }

    // Add event listeners.
    container?.addEventListener('click', onClick, {once: true});
  }

}( jQuery ));