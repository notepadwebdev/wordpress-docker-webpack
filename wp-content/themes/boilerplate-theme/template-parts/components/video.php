<?php 
  // echo '<div>Video (embed)</div>';
  // echo '<pre>';
  // print_r($args);
  // echo '</pre>';

  // Youtube Test Video URL
  // https://www.youtube.com/watch?v=C0DPdy98e4c

  $source = $args['source'] ?: 'youtube';
  $posterPath = (isset($args['poster']) && $args['poster']) ? $args['poster']['sizes']['1440x810'] : '';
  $preview = "<div class='video__poster'><img src='{$posterPath}' alt='' /></div>";

  $classNames = 'video animate-on-enter';
  $containerClassNames = 'video__container';
?>

<div class="<?php echo $classNames; ?>">
  <div class="<?php echo $containerClassNames; ?>">

    <?php
      switch($source) {
        
        case 'youtube':
          $iframe = $args['vid_embed'];
          $iframe = str_replace('youtube.com/embed/', 'youtube-nocookie.com/embed/', $iframe);
          
          // Use preg_match to find iframe src.
          preg_match('/src="(.+?)"/', $iframe, $matches);
          $src = $matches[1];

          // Get the YouTube ID.
          preg_match('/embed(.*?)?feature/', $src, $matches_id );
          $videoId = $matches_id[1];
          $videoId = str_replace( str_split( '?/' ), '', $videoId );

          echo "<div class='video-embed' data-youtube-id='{$videoId}' data-src='{$src}'></div>";
          break;

        case 'mp4':
          ?>
          <video 
            class="video-mp4" 
            preload="auto" 
            <?php if (isset($args['controls']) && $args['controls'] == 1) { echo 'controls'; } ?>
            <?php if (isset($args['loop']) && $args['loop'] == 1) { echo 'loop'; } ?>
            <?php if (isset($args['autoplay']) && $args['autoplay'] == 1) { echo 'autoplay muted playsinline'; } ?>
            poster="<?php echo $posterPath; ?>" 
          >
            <source src="<?php echo $args['video_file']['url']; ?>" type="video/mp4"></source>
            
            <?php /* ?>
            <?php if ($args['captions']) : ?>  
              <track kind="subtitles" srclang="en" src="<?php echo $args['captions']; ?>" default />
            <?php endif; ?>
            <?php */ ?>

          </video>
          <?php
          break;
      }
    ?>

    <?php echo $preview; ?>

  </div>
</div>