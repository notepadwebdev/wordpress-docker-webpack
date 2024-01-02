<?php
  // Block Pad.
  $blockPadArray = get_field('block_pad') ?: array('block_padding' => array('block-pad'));
  $blockPad = implode(' ', $blockPadArray['block_padding']);

  $noOfColumns = (int)get_field('number_of_columns');
  $column1 = get_field('column_1');
  $column2 = get_field('column_2');
  $column3 = get_field('column_3');

  switch ($noOfColumns) {
    case 1: 
      $layout = get_field('layout_1_col')['layout'];
      break;

    case 2: 
      $layout = get_field('layout_2_col')['layout'];
      $columnDirection = get_field('layout_2_col')['column_direction'];
      break;

    case 3: 
      $layout = get_field('layout_3_col')['layout'];
      break;
  }

  $className = 'content-block block';
  $className .= ' content-block--'.$layout;

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
  data-columns="<?php echo $noOfColumns; ?>"
>
  <div class="container theme-grid">

    <?php if ( ($column1 && $column1['flexible_content']) || ($column2 && $column2['flexible_content']) || ($column3 && $column3['flexible_content']) ) : ?>

      <?php 
        // Column 1.
        if (have_rows('column_1')) : 
          while (have_rows('column_1')) : the_row(); 
            ?>

            <div class="content-block__column content-block__column-1 flexible-content">
              <?php
                if ($column1 && $column1['flexible_content']) :
                  get_template_part('template-parts/components/flexible-content', null, $column1); 
                endif;
              ?>
            </div>

            <?php 
          endwhile; 
        endif; 
      ?>

      <?php 
        // Column 2.
        if ($noOfColumns >= 2 && have_rows('column_2')) : 
          while (have_rows('column_2')) : the_row(); 
            ?>
          
            <div class="content-block__column content-block__column-2 flexible-content">
              <?php
                if ($column2 && $column2['flexible_content']) :
                  get_template_part('template-parts/components/flexible-content', null, $column2); 
                endif;
              ?>
            </div>
            
            <?php 
          endwhile; 
        endif; 
      ?>

      <?php 
        // Column 3.
        if ($noOfColumns >= 3 && have_rows('column_3')) : 
          while (have_rows('column_3')) : the_row(); 
            ?>
          
            <div class="content-block__column content-block__column-3 flexible-content">
              <?php
                if ($column3 && $column3['flexible_content']) :
                  get_template_part('template-parts/components/flexible-content', null, $column3); 
                endif;
              ?>
            </div>
            
            <?php 
          endwhile; 
        endif; 
      ?>

    <?php else: ?>

      <?php /* Initial display after adding the unpopulated block. */ ?>
      <h2 class="block__placeholder">Content Block</h2>

    <?php endif; ?>

  </div>
</section>
