(function($){
    
    var initializeBlock = function( $block ) {
        
      const block = ($block.hasClass('temlpate')) ? $block.get(0) : $block.find('.temlpate').get(0);
      
      const container = block.querySelector('.container');
      if (container) {        
        
        // Do something with thing.
        console.log('temlpate', container);
        
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
        $('.temlpate').each(function(){
            initializeBlock( $(this) );
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=temlpate', initializeBlock );
    }

})(jQuery);
