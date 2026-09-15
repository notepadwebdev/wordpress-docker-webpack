(function($){
    
  var initializeBlock = function( $block ) {
      
    const block = ($block.hasClass('content-block')) ? $block.get(0) : $block.find('.content-block').get(0);

    if (!block) return;
    
    // Video Embeds.
    const videoYoutubes = block.querySelectorAll('.video-embed');
    [...videoYoutubes].forEach(videoYoutube => {
      $(videoYoutube).videoYoutube();
    });

    // Video MP4.
    const videoMP4s = block.querySelectorAll('.video-mp4');
    [...videoMP4s].forEach(videoMP4 => {
      $(videoMP4).videoMp4();
    });

  }

  // Initialize each block on page load (front end).
  $(document).ready(function(){
    $('.content-block').each(function(){
      initializeBlock( $(this) );
    });
  });

  // Initialize dynamic block preview (editor).
  if( window.acf ) {
    window.acf.addAction( 'render_block_preview/type=content-block', initializeBlock );
  }

})(jQuery);