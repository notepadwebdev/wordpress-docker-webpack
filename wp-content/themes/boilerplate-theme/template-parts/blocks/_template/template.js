(function($){
    
    var initializeBlock = function( $block ) {
        
      const block = ($block.hasClass('named-block')) ? $block.get(0) : $block.find('.named-block').get(0);
      
      const thing = block.querySelector('.named-block__thing');
      if (thing) {        
        
        // Do something with thing.
        console.log('named-block', thing);
        
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

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        $('.named-block').each(function(){
            initializeBlock( $(this) );
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=named-block', initializeBlock );
    }

})(jQuery);
