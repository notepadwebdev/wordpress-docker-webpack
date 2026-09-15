<?php
  $style = '';
  $displayMode = $block['displayMode'] ?? 'preview';
  $editMode = get_field('edit_mode') ?: 'layout';
  $columnDirection = 'l2r';

  $layoutJson = get_field('layout');
  $layout = $layoutJson ? json_decode($layoutJson, true) : [];

  // Block Pad.
  $blockPadArray = get_field('block_pad') ?: array('block_padding' => array('block-pad'));
  $blockPad = implode(' ', $blockPadArray['block_padding']);

  $contentCount = (int)get_field('content_count');
  $content1 = get_field('content_1');

  $className = 'content-block block';

  if (isset($columnDirection)) {
    $className .= ' content-block--'.$columnDirection;
  }
  
  if (!empty($block['align'])) {
    $className .= ' block--'.$block['align'].'-width';
  }
  if (!empty($block['align_text'])) {
    $className .= ' align-text-' . $block['align_text'];
  }
  if (!empty($block['align_content'])) {
    $className .= ' align-content-' . $block['align_content'];
  }

  if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
  }
?>

<section 
  <?php if (array_key_exists('anchor', $block)) { echo "id='{$block['anchor']}'"; } ?>
  class="<?php echo esc_attr($className); ?> body-pad <?php echo $blockPad; ?>" 
  data-columns="<?php echo $contentCount; ?>" 
  style="<?php echo esc_attr($style); ?>" 
  <?php if (get_field('theme')) : ?>
    data-theme="<?php the_field('theme'); ?>"
  <?php endif; ?>
>
  <div class="container">

    <?php
      if (get_field('include_block_header')) : 
        get_template_part('template-parts/components/block-header');
      endif;
    ?>

    <div class="theme-grid">

      <?php if ( $displayMode === 'layout' && is_admin() && current_user_can('edit_posts') ) : ?>

        <?php
          /**
           *  Grid Layout Builder.
           */
          $gridSettings = get_field('layout');
        ?>
        <div class="grid-layout-builder glb" id="grid-layout-builder-<?php echo $block['id']; ?>">
          <?php 
            get_template_part('template-parts/apps/grid-layout-builder/app', null, array(
              'gridCols' => 12, 
              'contentCount' => $contentCount, 
              'gridSettings' => $gridSettings,
              'blockId' => $block['id']
            )); 
          ?>
        </div>
  
      <?php elseif ( $displayMode === 'edit' ) : ?>
        <p>Edit Mode: ACF fields are shown in the sidebar.</p>
        <!-- Optionally, show some content or fields here -->

      <?php elseif ( ($content1 && $content1['flexible_content']) ) : ?>
        
        <?php 
          $content = $layout['contents'] ?? array();
          $direction = $layout['direction'] ?? $columnDirection;

          // Render content areas dynamically based on content count.
          for ($i = 1; $i <= $contentCount; $i++) : 
            $contentField = get_field('content_' . $i);
            $fieldName = 'content_' . $i;
            
            if (have_rows($fieldName)) : 
              while (have_rows($fieldName)) : the_row(); 
                $grid = $content[$i - 1]['columns'] ?? array('start' => 1, 'span' => 12);
                $start = $direction === 'l2r' ? $grid['start'] : (14 - $grid['start'] - $grid['span']);
                ?>
              
                <div class="content-block__column flexible-content" style="--start: <?php echo $start; ?>; --width: <?php echo $grid['span']; ?>;">
                  <?php
                    if ($contentField && $contentField['flexible_content']) :
                      get_template_part('template-parts/components/flexible-content', null, $contentField); 
                    endif;
                  ?>
                </div>
                
                <?php 
              endwhile; 
            endif; 
          endfor;
        ?>

      <?php else: ?>

        <?php /* Initial display after adding the unpopulated block. */ ?>
        <h2 class="block__placeholder">Content Block</h2>

      <?php endif; ?>

    </div>

  </div>
</section>
