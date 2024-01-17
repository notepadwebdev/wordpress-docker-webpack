document.addEventListener("DOMContentLoaded", () => { 
    
  const initializeBlock = (block) => {
      
    const container = block.querySelector('.container');
    if (container) {        
      
      // Do something with thing.
      console.log('template', container);
      
      // // Timeline.
      // var blockTL = gsap.timeline({paused: true});

      // // Observer.
      // const blockObserver = new IntersectionObserver((entries) => {
      //   (entries[0].isIntersecting) ? blockTL.play() : blockTL.pause();
      // }, {
      //   threshold: 0
      // });
      // blockObserver.observe(thing);
    }
  }

  // Initialize all block instances (Front end).
  const blocks = document.querySelectorAll('.template');
  [...blocks].forEach(block => {
    initializeBlock(block);
  });

  // Initialize block preview (CMS).
  if( window.acf ) {
    window.acf.addAction( 'render_block_preview/type=template', ($block) => {
      initializeBlock($block.get(0));
    });
  }

});
