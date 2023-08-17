/**
 * 	Gutenberg tidy up.
 */
// wp.domReady( () => {
	
	// Find blocks styles.
	// wp.blocks.getBlockTypes().forEach((block) => {
	// 	if (_.isArray(block['styles'])) {
	// 		console.log(block.name, _.pluck(block['styles'], 'name'));
	// 	}
	// });
	
	// Remove editor panels.
	// wp.data.dispatch('core/edit-post').removeEditorPanel('discussion-panel'); // Discussion
	// wp.data.dispatch('core/edit-post').removeEditorPanel('taxonomy-panel-post_tag'); // Tags
	
	// Unregister block styles.
	// wp.blocks.unregisterBlockStyle('core/image', 'default');
	// wp.blocks.unregisterBlockStyle('core/image', 'rounded');

// });


/**
 * 	Custom JavaScript.
 */
window.addEventListener(`load`, () => {

  /**
   * 	Embed blocks.
   */
	// const allowedEmbedBlocks = ['spotify', 'youtube'];
  // wp.blocks.getBlockVariations('core/embed').forEach(function (blockVariation) {
  //   if (-1 === allowedEmbedBlocks.indexOf(blockVariation.name)) {
  //     wp.blocks.unregisterBlockVariation('core/embed', blockVariation.name);
  //   }
  // });
	
});
