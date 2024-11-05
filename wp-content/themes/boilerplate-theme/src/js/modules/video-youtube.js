(function ( $ ) {
  
  $.fn.videoYoutube = function(options) { 

    // DOM.
    const el = this.get(0);     
    if (!el) {
      return;
    }

    const id = el.dataset.youtubeId;
    const src = el.dataset.src;

    const videoEl = el.closest('.video');
    const container = videoEl.querySelector('.video__container');
    const poster = videoEl.querySelector('.video__poster');
    
    // Dynamic iframe embed.
    const iframe = document.createElement("iframe");
    iframe.setAttribute(`src`, `${src}&amp;controls=0&amp;hd=1&amp;autohide=1`);
    iframe.setAttribute(`frameborder`, `0`);
    iframe.setAttribute(`allow`, `accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share`);
    iframe.setAttribute(`referrerpolicy`, `strict-origin-when-cross-origin`);
    iframe.setAttribute(`allowfullscreen`, `true`);
    iframe.onload = function() {
      videoEl.classList.remove('loading');
      videoEl.classList.add('loaded');
      poster?.remove();
    }

    const loadIframe = e => {
      if (!src) {
        e.preventDefault();
      } else {
        videoEl.classList.add('loading');
        el.before(iframe);
      }
    }
    
    // Add event listeners.
    container?.addEventListener('click', loadIframe, {once: true});
  
  }

}( jQuery ));