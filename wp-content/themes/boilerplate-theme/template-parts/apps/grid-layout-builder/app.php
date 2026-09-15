<?php
/**
 * Template part for displaying the grid layout builder app.
 *
 * @package TheCore
 */
$args = $args ?? [];
$contentCount = $args['contentCount'] ?? 2;
$gridCols = $args['gridCols'] ?? 12;
$blockId = $args['blockId'] ?? '';
$gridSettings = $args['gridSettings'] ?? '';
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/apps/grid-layout-builder/css/styles.css">
<div class="container" data-content-count="<?php echo esc_attr($contentCount); ?>" data-grid-cols="<?php echo esc_attr($gridCols); ?>" data-grid-settings="<?php echo esc_attr($gridSettings); ?>">
  <div class="grid-wrapper">
    <div class="grid-container">
      <svg id="grid-svg-<?php echo esc_attr($blockId); ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1282 300" preserveAspectRatio="xMidYMid meet">
        <defs>
          <style><![CDATA[
            .border { fill: none; stroke: #ccc; stroke-width: 1 }
            .col { fill: #f2f2f2; stroke: none }
            .label-box { fill: #f8d7d7; stroke: #e6baba; stroke-width: 1; rx:4; ry:4 }
            .label-text { font-family: Inter, Roboto, Arial, sans-serif; font-size: 12px; fill:#6b2626; dominant-baseline:middle; text-anchor:middle }
            .col-index { font-family: Inter, Roboto, Arial, sans-serif; font-size: 14px; fill:#a33; font-weight:700 }
            .content { fill: rgba(2, 117, 216, 0.10); stroke: #0275d8; stroke-width: 1; }
            .handle { fill: #ffffff; stroke: #0275d8; stroke-width: 1; cursor: ew-resize }
            .handle.left { transform-origin: center; }
            .handle.right { transform-origin: center; }
            .content-text { font-family: Inter, Roboto, Arial, sans-serif; font-size: 16px; fill:#03314b; font-weight:600; text-anchor:middle; dominant-baseline:middle; pointer-events:none }
            .delete-handle { display: none; cursor: pointer }
            .content-group:hover .delete-handle { display: block }
            .delete-circle { fill: #cccccc; stroke: #999999; stroke-width: 1 }
            .delete-text { font-size: 12px; fill: #666666; text-anchor: middle; pointer-events: none }
            .front-handle { display: none; cursor: pointer }
            .content-group:hover .front-handle { display: block }
            .front-circle { fill: #cccccc; stroke: #999999; stroke-width: 1; r: 8 }
            .front-text { font-size: 12px; fill: #666666; text-anchor: middle; pointer-events: none }
            .back-handle { display: none; cursor: pointer }
            .content-group:hover .back-handle { display: block }
            .back-circle { fill: #cccccc; stroke: #999999; stroke-width: 1; r: 8 }
            .back-text { font-size: 12px; fill: #666666; text-anchor: middle; pointer-events: none }
          ]]></style>
        </defs>

        <!-- outer border -->
        <rect x="1" y="1" width="1280" height="298" class="border"/>

        <!-- columns -->
        <g id="columns-<?php echo esc_attr($blockId); ?>">
          <!-- Columns will be generated dynamically by JavaScript -->
        </g>

        <!-- top labels -->
        <g id="labels-top-<?php echo esc_attr($blockId); ?>">
          <!-- Labels will be generated dynamically by JavaScript -->
        </g>

        <!-- bottom labels -->
        <g id="labels-bottom-<?php echo esc_attr($blockId); ?>">
          <!-- Labels will be generated dynamically by JavaScript -->
        </g>

        <!-- contents -->
        <g id="contents-<?php echo esc_attr($blockId); ?>">
          <!-- Contents will be generated dynamically by JavaScript -->
        </g>
      </svg>
    </div>
  </div>

  <div class="controls">
    <div class="actions-row">
      <div class="content-count-controls">
        <span>Content Areas:</span>
        <div class="increment-button-group">
          <button id="removeContent-<?php echo esc_attr($blockId); ?>" class="icon-button minus" aria-label="Remove Content">−</button>
          <button id="addContent-<?php echo esc_attr($blockId); ?>" class="icon-button plus" aria-label="Add Content">+</button>
        </div>
      </div>
      <div class="direction-controls">
        <span>Direction:</span>
        <div class="radio-button-group">
          <input type="radio" id="l2r-<?php echo esc_attr($blockId); ?>" name="direction-<?php echo esc_attr($blockId); ?>" value="l2r" checked>
          <label for="l2r-<?php echo esc_attr($blockId); ?>">Left to Right</label>
          <input type="radio" id="r2l-<?php echo esc_attr($blockId); ?>" name="direction-<?php echo esc_attr($blockId); ?>" value="r2l">
          <label for="r2l-<?php echo esc_attr($blockId); ?>">Right to Left</label>
        </div>
      </div>
      <div class="button-group">
        <button id="resetLayout-<?php echo esc_attr($blockId); ?>" class="secondary hidden">Undo</button>
        <button id="setLayout-<?php echo esc_attr($blockId); ?>" class="primary">Apply Layout</button>
      </div>
    </div>
  </div>
</div>
