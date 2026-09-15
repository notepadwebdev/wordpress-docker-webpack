(function (wp) {
  const { addFilter } = wp.hooks;
  const { BlockControls } = wp.blockEditor;
  const { ToolbarGroup, ToolbarButton } = wp.components;

  addFilter('blocks.registerBlockType', 'custom-block-controls', function (settings, name) {
    if (name === 'acf/content-block') {
      const originalEdit = settings.edit;
      settings.edit = function (props) {
        const { attributes, setAttributes } = props;
        const setDisplayMode = (displayMode) => {
          if (wp.data && props.clientId) {
            wp.data.dispatch('core/block-editor').updateBlockAttributes(props.clientId, { displayMode });
            return;
          }

          setAttributes({ displayMode });
        };
        return wp.element.createElement(
          wp.element.Fragment,
          {},
          wp.element.createElement(
            BlockControls,
            {},
            wp.element.createElement(
              ToolbarGroup,
              {},
              wp.element.createElement(ToolbarButton, {
                icon: 'visibility',
                label: 'Preview Mode',
                onClick: () => setDisplayMode('preview'),
                isPressed: attributes.displayMode === 'preview'
              }),
              wp.element.createElement(ToolbarButton, {
                icon: 'layout',
                label: 'Layout Mode',
                onClick: () => setDisplayMode('layout'),
                isPressed: attributes.displayMode === 'layout'
              })
            )
          ),
          originalEdit ? originalEdit(props) : null
        );
      };
    }
    return settings;
  });
})(window.wp);