/**
 * 	Gutenberg tidy up.
 */
wp.domReady( () => {
	
	// Find blocks styles.
	// wp.blocks.getBlockTypes().forEach((block) => {
	// 	if (_.isArray(block['styles'])) {
	// 		console.log(block.name, _.pluck(block['styles'], 'name'));
	// 	}
	// });
	
	wp.data.dispatch('core/edit-post').removeEditorPanel('discussion-panel'); // Discussion
	wp.data.dispatch('core/edit-post').removeEditorPanel('taxonomy-panel-post_tag'); // Tags
	
	wp.blocks.unregisterBlockStyle('core/image', 'default');
	wp.blocks.unregisterBlockStyle('core/image', 'rounded');
	
	wp.blocks.unregisterBlockStyle('core/pullquote', 'default');
	wp.blocks.unregisterBlockStyle('core/pullquote', 'solid-color');

});
