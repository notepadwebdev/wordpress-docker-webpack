window.initializeGridBuilder = function(blockId, options = {}) {
  const rootElement = options.rootElement || null;
  const previewDocument = rootElement ? rootElement.ownerDocument : document;
  const previewWindow = previewDocument.defaultView || window;
  const adminWindow = previewWindow.parent && previewWindow.parent !== previewWindow
    ? previewWindow.parent
    : previewWindow;
  const adminDocument = adminWindow.document;
  const svg = options.svg || (rootElement
    ? rootElement.querySelector('#grid-svg-' + blockId)
    : previewDocument.getElementById('grid-svg-' + blockId));
  if (!svg) return;
  if (svg.dataset.gridBuilderInitialized === 'true') return;
  svg.dataset.gridBuilderInitialized = 'true';

  const createSvgElement = (tagName) => previewDocument.createElementNS('http://www.w3.org/2000/svg', tagName);
  const getPreviewElementById = (id) => previewDocument.getElementById(id);
  const queryPreview = (selector) => previewDocument.querySelector(selector);
  const queryPreviewAll = (selector) => previewDocument.querySelectorAll(selector);
  const queryAdmin = (selector) => adminDocument.querySelector(selector);

  // Get config from data attributes
  function getConfig() {
    const container = svg.closest('.container');
    return {
      gridCols: parseInt(container.dataset.gridCols) || 12,
      contentCount: parseInt(container.dataset.contentCount) || 2
    };
  }

  const config = getConfig();
  const { gridCols } = config;
  let contentCount = config.contentCount;

  // Direction state
  let currentDirection = 'l2r'; // 'l2r' or 'r2l'

  // Calculate SVG dimensions based on grid columns
  const columnWidth = 84.5;
  const gapWidth = 24;
  const totalWidth = (gridCols * columnWidth) + ((gridCols - 1) * gapWidth) + 2; // +2 for borders
  const height = 300;
  // const handlePadding = 4; // Half the width of drag handles (8px wide)

  // Update SVG viewBox with padding for drag handles
  svg.setAttribute('viewBox', `-10 0 ${totalWidth + 20} ${height}`);

  // Generate grid columns
  function generateGrid() {
    const columnsGroup = svg.querySelector('#columns-' + blockId);
    const labelsTopGroup = svg.querySelector('#labels-top-' + blockId);
    const labelsBottomGroup = svg.querySelector('#labels-bottom-' + blockId);

    // Clear existing content
    columnsGroup.innerHTML = '';
    labelsTopGroup.innerHTML = '';
    labelsBottomGroup.innerHTML = '';

    // Generate columns
    for (let i = 0; i < gridCols; i++) {
      const x = 2 + (i * (columnWidth + gapWidth));
      const rect = createSvgElement('rect');
      rect.setAttribute('x', x);
      rect.setAttribute('y', '20');
      rect.setAttribute('width', columnWidth);
      rect.setAttribute('height', '260');
      rect.classList.add('col');
      columnsGroup.appendChild(rect);

      // Generate top label
      const labelTopGroup = createSvgElement('g');
      const labelTopX = x + (columnWidth / 2);
      labelTopGroup.setAttribute('transform', `translate(${labelTopX},10)`);

      const labelTopBox = createSvgElement('rect');
      labelTopBox.setAttribute('x', '-14');
      labelTopBox.setAttribute('y', '-10');
      labelTopBox.setAttribute('width', '28');
      labelTopBox.setAttribute('height', '20');
      labelTopBox.classList.add('label-box');

      const labelTopText = createSvgElement('text');
      labelTopText.classList.add('label-text');
      labelTopText.textContent = (i + 1).toString();

      labelTopGroup.appendChild(labelTopBox);
      labelTopGroup.appendChild(labelTopText);
      labelsTopGroup.appendChild(labelTopGroup);

      // Generate bottom label
      const labelBottomGroup = createSvgElement('g');
      labelBottomGroup.setAttribute('transform', `translate(${labelTopX},290)`);

      const labelBottomBox = createSvgElement('rect');
      labelBottomBox.setAttribute('x', '-14');
      labelBottomBox.setAttribute('y', '-10');
      labelBottomBox.setAttribute('width', '28');
      labelBottomBox.setAttribute('height', '20');
      labelBottomBox.classList.add('label-box');

      const labelBottomText = createSvgElement('text');
      labelBottomText.classList.add('label-text');
      labelBottomText.textContent = (i + 1).toString();

      labelBottomGroup.appendChild(labelBottomBox);
      labelBottomGroup.appendChild(labelBottomText);
      labelsBottomGroup.appendChild(labelBottomGroup);
    }

    // Update border width
    const border = svg.querySelector('rect.border');
    border.setAttribute('width', totalWidth);
  }

  // Generate content areas
  function generateContent() {
    const contentsGroup = svg.querySelector('#contents-' + blockId);
    contentsGroup.innerHTML = '';

    // Calculate equal distribution
    const columnsPerContent = Math.floor(gridCols / contentCount);
    const remainingColumns = gridCols % contentCount;

    let currentColumn = 0;

    for (let i = 0; i < contentCount; i++) {
      // Distribute remaining columns to first few contents
      const extraColumn = i < remainingColumns ? 1 : 0;
      const contentColumns = columnsPerContent + extraColumn;

      const startColumn = currentColumn;
      const endColumn = startColumn + contentColumns - 1;

      // Calculate pixel positions
      const startX = 2 + (startColumn * (columnWidth + gapWidth));
      const endX = 2 + ((endColumn + 1) * (columnWidth + gapWidth)) - gapWidth;
      const width = endX - startX;

      // Create content group
      const contentGroup = createSvgElement('g');
      contentGroup.id = `content-${i + 1}`;
      contentGroup.setAttribute('data-index', i + 1);
      contentGroup.classList.add('content-group');

      // Create content rectangle
      const contentRect = createSvgElement('rect');
      contentRect.classList.add('content');
      contentRect.setAttribute('x', startX);
      contentRect.setAttribute('y', '40');
      contentRect.setAttribute('width', width);
      contentRect.setAttribute('height', '220');
      contentRect.setAttribute('rx', '4');

      // Create left handle
      const handleLeft = createSvgElement('rect');
      handleLeft.classList.add('handle', 'left');
      handleLeft.setAttribute('x', startX - 4);
      handleLeft.setAttribute('y', '130');
      handleLeft.setAttribute('width', '8');
      handleLeft.setAttribute('height', '40');
      handleLeft.setAttribute('rx', '3');
      handleLeft.setAttribute('data-side', 'left');

      // Create right handle
      const handleRight = createSvgElement('rect');
      handleRight.classList.add('handle', 'right');
      handleRight.setAttribute('x', endX - 4);
      handleRight.setAttribute('y', '130');
      handleRight.setAttribute('width', '8');
      handleRight.setAttribute('height', '40');
      handleRight.setAttribute('rx', '3');
      handleRight.setAttribute('data-side', 'right');

      // Create label
      const label = createSvgElement('text');
      label.classList.add('content-text');
      label.setAttribute('x', startX + (width / 2));
      label.setAttribute('y', '150');
      label.textContent = `Content ${i + 1}`;

      // Create delete handle
      const deleteGroup = createSvgElement('g');
      deleteGroup.classList.add('delete-handle');
      deleteGroup.setAttribute('transform', `translate(${startX + width - 25}, 45)`);

      const deleteCircle = createSvgElement('circle');
      deleteCircle.setAttribute('cx', '8');
      deleteCircle.setAttribute('cy', '8');
      deleteCircle.setAttribute('r', '8');
      deleteCircle.classList.add('delete-circle');

      const deleteText = createSvgElement('text');
      deleteText.classList.add('delete-text');
      deleteText.setAttribute('x', '8');
      deleteText.setAttribute('y', '12');
      deleteText.textContent = '×';

      deleteGroup.appendChild(deleteCircle);
      deleteGroup.appendChild(deleteText);

      // Create front handle
      const frontGroup = createSvgElement('g');
      frontGroup.classList.add('front-handle');
      frontGroup.setAttribute('transform', `translate(${startX + width - 50}, 45)`);

      const frontCircle = createSvgElement('circle');
      frontCircle.setAttribute('cx', '8');
      frontCircle.setAttribute('cy', '8');
      frontCircle.setAttribute('r', '8');
      frontCircle.classList.add('front-circle');

      const frontText = createSvgElement('text');
      frontText.classList.add('front-text');
      frontText.setAttribute('x', '8');
      frontText.setAttribute('y', '12');
      frontText.textContent = '↑';

      frontGroup.appendChild(frontCircle);
      frontGroup.appendChild(frontText);

      // Create back handle
      const backGroup = createSvgElement('g');
      backGroup.classList.add('back-handle');
      backGroup.setAttribute('transform', `translate(${startX + width - 75}, 45)`);

      const backCircle = createSvgElement('circle');
      backCircle.setAttribute('cx', '8');
      backCircle.setAttribute('cy', '8');
      backCircle.setAttribute('r', '8');
      backCircle.classList.add('back-circle');

      const backText = createSvgElement('text');
      backText.classList.add('back-text');
      backText.setAttribute('x', '8');
      backText.setAttribute('y', '12');
      backText.textContent = '↓';

      backGroup.appendChild(backCircle);
      backGroup.appendChild(backText);

      contentGroup.appendChild(contentRect);
      contentGroup.appendChild(handleLeft);
      contentGroup.appendChild(handleRight);
      contentGroup.appendChild(label);
      contentGroup.appendChild(deleteGroup);
      contentGroup.appendChild(frontGroup);
      contentGroup.appendChild(backGroup);
      contentsGroup.appendChild(contentGroup);

      currentColumn = endColumn + 1;
    }
  }

  // Apply direction transformation
  function applyDirection() {
    const contents = Array.from(svg.querySelectorAll('g[id^="content-"]'));

    if (currentDirection === 'r2l' && contentCount > 1) {
      // Reverse the positions for RTL
      const totalGridWidth = (gridCols * columnWidth) + ((gridCols - 1) * gapWidth);

      contents.forEach((contentGroup, index) => {
        const rect = contentGroup.querySelector('rect.content');
        const left = contentGroup.querySelector('rect.handle.left');
        const right = contentGroup.querySelector('rect.handle.right');
        const label = contentGroup.querySelector('text.content-text');

        if (rect && left && right && label) {
          const currentX = parseFloat(rect.getAttribute('x'));
          const width = parseFloat(rect.getAttribute('width'));

          // Calculate new position from the right
          const newX = totalGridWidth - currentX - width + 2; // +2 for left border offset

          rect.setAttribute('x', newX);
          left.setAttribute('x', newX - 4);
          right.setAttribute('x', newX + width - 4);
          label.setAttribute('x', newX + (width / 2));
        }
      });
    }
  }

  // Load saved layout from ACF field
  function loadSavedLayout() {
    let savedValue = null;
    
    // First, try to get from data attribute (passed from PHP)
    const container = svg.closest('.container');
    if (container && container.dataset.gridSettings) {
      savedValue = container.dataset.gridSettings;
    }
    
    // Try to get value from ACF API
    if (!savedValue && adminWindow.acf && adminWindow.acf.getField) {
      const acfField = adminWindow.acf.getField('field_691de566ba490');
      if (acfField) {
        savedValue = acfField.val();
      }
    }
    
    // Fallback to DOM if ACF API didn't work
    if (!savedValue) {
      const layoutFieldContainer = queryAdmin('[data-key="field_691de566ba490"]');
      const layoutField = layoutFieldContainer ? layoutFieldContainer.querySelector('textarea') : null;
      if (layoutField && layoutField.value) {
        savedValue = layoutField.value;
      }
    }
    
    if (!savedValue || savedValue.trim() === '') {
      return false;
    }

    try {
      const savedData = JSON.parse(savedValue);

      if (savedData.direction) {
        currentDirection = savedData.direction;
      }

      if (savedData.contents && Array.isArray(savedData.contents)) {
        contentCount = savedData.contents.length;
        
        // Generate content blocks based on saved data
        const contentsGroup = svg.querySelector('#contents-' + blockId);
        contentsGroup.innerHTML = '';

        savedData.contents.forEach((contentData, i) => {
          const { columns } = contentData;
          
          if (!columns || columns.start === null || columns.end === null) {
            console.warn('Invalid column data for content', i + 1);
            return;
          }

          const startColumn = columns.start - 1; // Convert to 0-indexed
          const endColumn = columns.end - 1;

          // Calculate pixel positions
          const startX = 2 + (startColumn * (columnWidth + gapWidth));
          const endX = 2 + ((endColumn + 1) * (columnWidth + gapWidth)) - gapWidth;
          const width = endX - startX;

          // Create content group
          const contentGroup = createSvgElement('g');
          contentGroup.id = `content-${i + 1}`;
          contentGroup.setAttribute('data-index', i + 1);
          contentGroup.classList.add('content-group');

          // Create content rectangle
          const contentRect = createSvgElement('rect');
          contentRect.classList.add('content');
          contentRect.setAttribute('x', startX);
          contentRect.setAttribute('y', '40');
          contentRect.setAttribute('width', width);
          contentRect.setAttribute('height', '220');
          contentRect.setAttribute('rx', '4');

          // Create left handle
          const handleLeft = createSvgElement('rect');
          handleLeft.classList.add('handle', 'left');
          handleLeft.setAttribute('x', startX - 4);
          handleLeft.setAttribute('y', '130');
          handleLeft.setAttribute('width', '8');
          handleLeft.setAttribute('height', '40');
          handleLeft.setAttribute('rx', '3');
          handleLeft.setAttribute('data-side', 'left');

          // Create right handle
          const handleRight = createSvgElement('rect');
          handleRight.classList.add('handle', 'right');
          handleRight.setAttribute('x', endX - 4);
          handleRight.setAttribute('y', '130');
          handleRight.setAttribute('width', '8');
          handleRight.setAttribute('height', '40');
          handleRight.setAttribute('rx', '3');
          handleRight.setAttribute('data-side', 'right');

          // Create label
          const label = createSvgElement('text');
          label.classList.add('content-text');
          label.setAttribute('x', startX + (width / 2));
          label.setAttribute('y', '150');
          label.textContent = contentData.label || `Content ${i + 1}`;

          // Create delete handle
          const deleteGroup = createSvgElement('g');
          deleteGroup.classList.add('delete-handle');
          deleteGroup.setAttribute('transform', `translate(${startX + width - 25}, 45)`);

          const deleteCircle = createSvgElement('circle');
          deleteCircle.setAttribute('cx', '8');
          deleteCircle.setAttribute('cy', '8');
          deleteCircle.setAttribute('r', '8');
          deleteCircle.classList.add('delete-circle');

          const deleteText = createSvgElement('text');
          deleteText.classList.add('delete-text');
          deleteText.setAttribute('x', '8');
          deleteText.setAttribute('y', '12');
          deleteText.textContent = '×';

          deleteGroup.appendChild(deleteCircle);
          deleteGroup.appendChild(deleteText);

          // Create front handle
          const frontGroup = createSvgElement('g');
          frontGroup.classList.add('front-handle');
          frontGroup.setAttribute('transform', `translate(${startX + width - 50}, 45)`);

          const frontCircle = createSvgElement('circle');
          frontCircle.setAttribute('cx', '8');
          frontCircle.setAttribute('cy', '8');
          frontCircle.setAttribute('r', '8');
          frontCircle.classList.add('front-circle');

          const frontText = createSvgElement('text');
          frontText.classList.add('front-text');
          frontText.setAttribute('x', '8');
          frontText.setAttribute('y', '12');
          frontText.textContent = '↑';

          frontGroup.appendChild(frontCircle);
          frontGroup.appendChild(frontText);

          // Create back handle
          const backGroup = createSvgElement('g');
          backGroup.classList.add('back-handle');
          backGroup.setAttribute('transform', `translate(${startX + width - 75}, 45)`);

          const backCircle = createSvgElement('circle');
          backCircle.setAttribute('cx', '8');
          backCircle.setAttribute('cy', '8');
          backCircle.setAttribute('r', '8');
          backCircle.classList.add('back-circle');

          const backText = createSvgElement('text');
          backText.classList.add('back-text');
          backText.setAttribute('x', '8');
          backText.setAttribute('y', '12');
          backText.textContent = '↓';

          backGroup.appendChild(backCircle);
          backGroup.appendChild(backText);

          contentGroup.appendChild(contentRect);
          contentGroup.appendChild(handleLeft);
          contentGroup.appendChild(handleRight);
          contentGroup.appendChild(label);
          contentGroup.appendChild(deleteGroup);
          contentGroup.appendChild(frontGroup);
          contentGroup.appendChild(backGroup);
          contentsGroup.appendChild(contentGroup);
        });

        return true;
      }
    } catch (error) {
      console.error('Error loading saved layout:', error);
      return false;
    }

    return false;
  }

  // Initialize grid and content
  generateGrid();
  
  // Function to initialize content
  function initializeGridContent() {
    // Try to load saved layout, if not available use defaults
    const layoutLoaded = loadSavedLayout();
    if (!layoutLoaded) {
      generateContent();
    }
    
    applyDirection();
    
    // Update direction radio to match loaded state
    const directionRadio = queryPreview('input[name="direction-' + blockId + '"][value="' + currentDirection + '"]');
    if (directionRadio) {
      directionRadio.checked = true;
    }
    
    // Initialize event handlers for all content blocks
    initializeAllContents();
    updateAddButton();
  }
  
  // Initialize content immediately since data is available via data attribute
  initializeGridContent();


  // Build arrays of left and right column edge x positions from .col rects
  function getColumnEdgeLists() {
    const rects = Array.from(svg.querySelectorAll('rect.col'));
    const leftEdges = rects.map(r => parseFloat(r.getAttribute('x'))).sort((a, b) => a - b);
    const rightEdges = rects.map(r => parseFloat(r.getAttribute('x')) + parseFloat(r.getAttribute('width'))).sort((a, b) => a - b);
    return { leftEdges, rightEdges };
  }

  let { leftEdges, rightEdges } = getColumnEdgeLists();

  // Track if there are unsaved changes
  let hasUnsavedChanges = false;

  function markAsChanged() {
    hasUnsavedChanges = true;
    updateApplyButtonState();
  }

  function markAsSaved() {
    hasUnsavedChanges = false;
    updateApplyButtonState();
  }

  function updateApplyButtonState() {
    const applyBtn = getPreviewElementById('setLayout-' + blockId);
    const undoBtn = getPreviewElementById('resetLayout-' + blockId);
    
    if (applyBtn) {
      if (hasUnsavedChanges) {
        applyBtn.classList.add('has-changes');
      } else {
        applyBtn.classList.remove('has-changes');
      }
    }
    
    if (undoBtn) {
      if (hasUnsavedChanges) {
        undoBtn.classList.remove('hidden');
      } else {
        undoBtn.classList.add('hidden');
      }
    }
  }

  function nearestInList(list, x) {
    if (!list || list.length === 0) return x;
    let best = list[0];
    let min = Math.abs(x - best);
    for (let i = 1; i < list.length; i++) {
      const d = Math.abs(x - list[i]);
      if (d < min) {
        min = d;
        best = list[i];
      }
    }
    return best;
  }

  // Helper: convert client coords to svg coords
  function clientToSvg(clientX, clientY) {
    const pt = svg.createSVGPoint ? svg.createSVGPoint() : new previewWindow.DOMPoint();
    pt.x = clientX;
    pt.y = clientY;
    const ctm = svg.getScreenCTM();
    if (!ctm) return { x: clientX, y: clientY };
    const inv = ctm.inverse();
    const svgP = pt.matrixTransform(inv);
    return { x: svgP.x, y: svgP.y };
  }

  // Calculate which columns a content spans
  function calculateColumnSpan(x, width) {
    const startCol = leftEdges.findIndex(edge => Math.abs(edge - x) < 1);
    const endX = x + width;
    const endCol = rightEdges.findIndex(edge => Math.abs(edge - endX) < 1);
    return {
      start: startCol >= 0 ? startCol + 1 : null,
      end: endCol >= 0 ? endCol + 1 : null,
      span: endCol >= 0 && startCol >= 0 ? endCol - startCol + 1 : null
    };
  }

  // Wire up content handles
  function initializeContent(group) {
    const rect = group.querySelector('rect.content');
    const left = group.querySelector('rect.handle.left');
    const right = group.querySelector('rect.handle.right');
    const label = group.querySelector('text.content-text');
    const deleteHandle = group.querySelector('.delete-handle');
    if (!rect || !left || !right) return;

    // Double-click to bring to front
    group.addEventListener('dblclick', function() {
      const contentsGroup = svg.querySelector('#contents-' + blockId);
      contentsGroup.appendChild(group);
    });

    function updateHandles() {
      const x = parseFloat(rect.getAttribute('x'));
      const w = parseFloat(rect.getAttribute('width'));
      left.setAttribute('x', String(x - 4));
      right.setAttribute('x', String(x + w - 4));
      if (label) label.setAttribute('x', String(x + w / 2));
      // Update delete handle position
      const deleteHandle = group.querySelector('.delete-handle');
      if (deleteHandle) {
        deleteHandle.setAttribute('transform', `translate(${x + w - 25}, 45)`);
      }
      // Update front handle position
      const frontHandle = group.querySelector('.front-handle');
      if (frontHandle) {
        frontHandle.setAttribute('transform', `translate(${x + w - 50}, 45)`);
      }
      // Update back handle position
      const backHandle = group.querySelector('.back-handle');
      if (backHandle) {
        backHandle.setAttribute('transform', `translate(${x + w - 75}, 45)`);
      }
    }

    updateHandles();

    let active = null;

    function onPointerDownHandle(e) {
      e.preventDefault();
      const side = this.classList.contains('left') ? 'left' : 'right';
      active = side;
      this.setPointerCapture && this.setPointerCapture(e.pointerId);
      svg.addEventListener('pointermove', onPointerMove);
      previewDocument.addEventListener('pointerup', onPointerUp);
    }

    function onPointerMove(e) {
      if (!active) return;
      const p = clientToSvg(e.clientX, e.clientY);
      const totalGridWidth = (gridCols * columnWidth) + ((gridCols - 1) * gapWidth);
      const visualSnapLeft = currentDirection === 'l2r' ? leftEdges : rightEdges.map(e => totalGridWidth - e + 2);
      const visualSnapRight = currentDirection === 'l2r' ? rightEdges : leftEdges.map(e => totalGridWidth - e + 2);
      const snapX = active === 'left' ? nearestInList(visualSnapLeft, p.x) : nearestInList(visualSnapRight, p.x);
      const curX = parseFloat(rect.getAttribute('x'));
      const curW = parseFloat(rect.getAttribute('width'));

      if (active === 'left') {
        const rightEdge = curX + curW;
        const newLeft = Math.min(snapX, rightEdge - 1);
        rect.setAttribute('x', String(newLeft));
        rect.setAttribute('width', String(Math.max(1, rightEdge - newLeft)));
      } else if (active === 'right') {
        const leftEdge = curX;
        const newRight = Math.max(snapX, leftEdge + 1);
        rect.setAttribute('width', String(Math.max(1, newRight - leftEdge)));
      }
      updateHandles();
    }

    function onPointerUp(e) {
      try { left.releasePointerCapture && left.releasePointerCapture(e.pointerId); } catch(_) {}
      try { right.releasePointerCapture && right.releasePointerCapture(e.pointerId); } catch(_) {}
      active = null;
      svg.removeEventListener('pointermove', onPointerMove);
      previewDocument.removeEventListener('pointerup', onPointerUp);
      markAsChanged();
    }

    left.addEventListener('pointerdown', onPointerDownHandle);
    right.addEventListener('pointerdown', onPointerDownHandle);

    // Delete handle
    if (deleteHandle) {
      deleteHandle.addEventListener('click', function(e) {
        e.stopPropagation();
        // Remove this content
        group.remove();
        contentCount--;
        // Renumber remaining contents
        const remaining = Array.from(svg.querySelectorAll('g[id^="content-"]')).sort((a, b) => {
          const aId = parseInt(a.id.split('-')[1]);
          const bId = parseInt(b.id.split('-')[1]);
          return aId - bId;
        });
        remaining.forEach((g, idx) => {
          const newId = `content-${idx + 1}`;
          g.id = newId;
          const lbl = g.querySelector('text.content-text');
          if (lbl) lbl.textContent = `Content ${idx + 1}`;
          g.setAttribute('data-index', idx + 1);
        });
        // Reinitialize handles
        initializeAllContents();
        updateAddButton();
        markAsChanged();
      });
    }

    // Front handle
    const frontHandle = group.querySelector('.front-handle');
    if (frontHandle) {
      frontHandle.addEventListener('click', function(e) {
        e.stopPropagation();
        // Bring to front
        const contentsGroup = svg.querySelector('#contents-' + blockId);
        contentsGroup.appendChild(group);
        markAsChanged();
      });
    }

    // Back handle
    const backHandle = group.querySelector('.back-handle');
    if (backHandle) {
      backHandle.addEventListener('click', function(e) {
        e.stopPropagation();
        // Send to back
        const contentsGroup = svg.querySelector('#contents-' + blockId);
        contentsGroup.insertBefore(group, contentsGroup.firstChild);
        markAsChanged();
      });
    }
  }

  // Initialize all contents
  function initializeAllContents() {
    const contents = Array.from(svg.querySelectorAll('g[id^="content-"]'));
    contents.forEach(initializeContent);
  }

  function updateAddButton() {
    const addBtn = getPreviewElementById('addContent-' + blockId);
    const removeBtn = getPreviewElementById('removeContent-' + blockId);
    if (!addBtn || !removeBtn) {
      return;
    }
    addBtn.disabled = contentCount >= gridCols;
    removeBtn.disabled = contentCount <= 1;
  }

  // Set layout data (always exports LTR positions)
  const setLayoutButton = getPreviewElementById('setLayout-' + blockId);
  if (setLayoutButton) {
    setLayoutButton.addEventListener('click', function() {
    const contents = Array.from(svg.querySelectorAll('g[id^="content-"]'));
    let layoutData = contents.map(g => {
      const r = g.querySelector('rect.content');
      const label = g.querySelector('text.content-text');
      let x = parseFloat(r.getAttribute('x'));
      const width = parseFloat(r.getAttribute('width'));

      // If RTL, convert visual positions back to LTR
      if (currentDirection === 'r2l') {
        const totalGridWidth = (gridCols * columnWidth) + ((gridCols - 1) * gapWidth);
        x = totalGridWidth - x - width + 2;
      }

      const columnSpan = calculateColumnSpan(x, width);
      return {
        id: g.id,
        label: label ? label.textContent : '',
        x: x,
        width: width,
        columns: columnSpan
      };
    });

    const exportData = {
      direction: currentDirection,
      contents: layoutData.map(item => {
        const { id, x, width, ...rest } = item;
        return rest;
      })
    };
    const jsonString = JSON.stringify(exportData, null, 2);
    const appliedContentCount = exportData.contents.length;

    // Save to ACF Layout field (ACFE Code Editor)
    const layoutFieldContainer = queryAdmin('[data-key="field_691de566ba490"]');
    const layoutField = layoutFieldContainer ? layoutFieldContainer.querySelector('textarea') : null;
    if (layoutField) {
      layoutField.value = jsonString;
      
      // Trigger native events
      layoutField.dispatchEvent(new adminWindow.Event('input', { bubbles: true }));
      layoutField.dispatchEvent(new adminWindow.Event('change', { bubbles: true }));
      
      // Try to update CodeMirror if it exists
      if (layoutFieldContainer) {
        const codeMirrorElement = layoutFieldContainer.querySelector('.CodeMirror');
        if (codeMirrorElement && codeMirrorElement.CodeMirror) {
          codeMirrorElement.CodeMirror.setValue(jsonString);
        }
        
        // Try ACFE code editor update
        if (adminWindow.acf && adminWindow.acf.getField) {
          const acfField = adminWindow.acf.getField('field_691de566ba490');
          if (acfField && acfField.$input) {
            acfField.$input().val(jsonString);
            acfField.$input().trigger('change');
          }
        }
      }
      
      // Also update the data attribute directly so it's available immediately
      const container = svg.closest('.container');
      if (container) {
        container.dataset.gridSettings = jsonString;
      }
      
      // Trigger WordPress block editor to recognize the change
      if (adminWindow.wp && adminWindow.wp.data) {
        const { select, dispatch } = adminWindow.wp.data;
        
        // Find the current block
        const blockElement = svg.closest('[data-type="acf/content-block"]');
        if (blockElement) {
          const clientId = blockElement.getAttribute('data-block');
          if (clientId) {
            // Trigger a block update to mark it as dirty
            const block = select('core/block-editor').getBlock(clientId);
            if (block) {
              dispatch('core/block-editor').updateBlockAttributes(clientId, {
                data: {
                  ...block.attributes.data,
                  field_691de566ba490: jsonString,
                  field_691de5e0ba491: appliedContentCount.toString(),
                  _timestamp: Date.now()
                }
              });
            }
          }
        }
      }
    } else {
      console.error('Layout field textarea not found');
    }

    // Mark as saved
    markAsSaved();

    // Save content count to ACF Content Count field
    const contentCountFieldContainer = queryAdmin('[data-key="field_691de5e0ba491"]');
    const contentCountField = contentCountFieldContainer ? contentCountFieldContainer.querySelector('input[type="number"]') : null;
    if (contentCountField) {
      contentCountField.value = appliedContentCount;
      contentCountField.dispatchEvent(new adminWindow.Event('input', { bubbles: true }));
      contentCountField.dispatchEvent(new adminWindow.Event('change', { bubbles: true }));
    }

    // Switch to Preview Mode after applying layout and force re-render
    // We need to give ACF time to process the field changes before switching modes
    setTimeout(() => {
      if (adminWindow.wp && adminWindow.wp.data) {
        const { select, dispatch } = adminWindow.wp.data;
        const blockElement = svg.closest('[data-type="acf/content-block"]');
        if (blockElement) {
          const clientId = blockElement.getAttribute('data-block');
          if (clientId) {
            // Switch to preview mode
            dispatch('core/block-editor').updateBlockAttributes(clientId, {
              displayMode: 'preview'
            });
          }
        }
      }
    }, 200);
    });
  }

  // Reset layout
  const resetLayoutButton = getPreviewElementById('resetLayout-' + blockId);
  if (resetLayoutButton) {
    resetLayoutButton.addEventListener('click', function() {
    // Try to reload from saved layout first
    const layoutLoaded = loadSavedLayout();
    if (!layoutLoaded) {
      // If no saved layout, generate default
      generateContent();
    }
    applyDirection();
    initializeAllContents();
    updateAddButton();
    
    // Update direction radio to match loaded state
    const directionRadio = queryPreview('input[name="direction-' + blockId + '"][value="' + currentDirection + '"]');
    if (directionRadio) {
      directionRadio.checked = true;
    }
    
    // Mark as saved since we're resetting to saved state
    markAsSaved();
    });
  }

  // Add content
  const addContentButton = getPreviewElementById('addContent-' + blockId);
  if (addContentButton) {
    addContentButton.addEventListener('click', function() {
    if (contentCount >= gridCols) return;
    contentCount++;
    generateContent();
    applyDirection();
    initializeAllContents();
    updateAddButton();
    markAsChanged();
    });
  }

  // Remove content (removes last content area)
  const removeContentButton = getPreviewElementById('removeContent-' + blockId);
  if (removeContentButton) {
    removeContentButton.addEventListener('click', function() {
    if (contentCount <= 1) return;
    
    // Find all content groups and remove the last one
    const contents = Array.from(svg.querySelectorAll('g[id^="content-"]')).sort((a, b) => {
      const aIndex = parseInt(a.getAttribute('data-index'));
      const bIndex = parseInt(b.getAttribute('data-index'));
      return aIndex - bIndex;
    });
    
    if (contents.length > 0) {
      // Remove the last content
      const lastContent = contents[contents.length - 1];
      lastContent.remove();
      contentCount--;
      
      // Renumber remaining contents
      const remaining = Array.from(svg.querySelectorAll('g[id^="content-"]')).sort((a, b) => {
        const aIndex = parseInt(a.getAttribute('data-index'));
        const bIndex = parseInt(b.getAttribute('data-index'));
        return aIndex - bIndex;
      });
      remaining.forEach((g, idx) => {
        g.id = `content-${idx + 1}`;
        g.setAttribute('data-index', idx + 1);
        const label = g.querySelector('text.content-text');
        if (label) label.textContent = `Content ${idx + 1}`;
      });
      
      // Reinitialize handles
      initializeAllContents();
      updateAddButton();
      markAsChanged();
    }
    });
  }

  // Direction selector
  const directionRadios = queryPreviewAll('input[name="direction-' + blockId + '"]');
  
  // Attach event listeners to radio inputs
  directionRadios.forEach(radio => {
    radio.addEventListener('change', function() {
      const newDirection = this.value;
      if (newDirection === currentDirection) return;

      const contents = Array.from(svg.querySelectorAll('g[id^="content-"]'));
      const totalGridWidth = (gridCols * columnWidth) + ((gridCols - 1) * gapWidth);

      contents.forEach((contentGroup) => {
        const rect = contentGroup.querySelector('rect.content');
        const left = contentGroup.querySelector('rect.handle.left');
        const right = contentGroup.querySelector('rect.handle.right');
        const label = contentGroup.querySelector('text.content-text');

        if (rect && left && right && label) {
          const currentX = parseFloat(rect.getAttribute('x'));
          const width = parseFloat(rect.getAttribute('width'));

          let newX;
          if (newDirection === 'r2l') {
            // Flip to R2L
            newX = totalGridWidth - currentX - width + 2;
          } else {
            // Flip back to L2R
            newX = totalGridWidth - currentX - width + 2;
          }

          rect.setAttribute('x', newX);
          left.setAttribute('x', newX - 4);
          right.setAttribute('x', newX + width - 4);
          label.setAttribute('x', newX + (width / 2));
        }
      });

      currentDirection = newDirection;
      markAsChanged();
    });
  });

  // Also attach click listeners to labels for better UX
  const directionLabels = queryPreviewAll('label[for^="l2r-' + blockId + '"], label[for^="r2l-' + blockId + '"]');
  directionLabels.forEach(label => {
    label.addEventListener('click', function() {
      const forId = this.getAttribute('for');
      const radio = getPreviewElementById(forId);
      if (radio) {
        radio.checked = true;
        radio.dispatchEvent(new previewWindow.Event('change'));
      }
    });
  });

  // Sync radio button state with currentDirection
  const directionRadio = queryPreview('input[name="direction-' + blockId + '"][value="' + currentDirection + '"]');
  if (directionRadio) {
    directionRadio.checked = true;
  }

  // Recompute edges on window resize
  previewWindow.addEventListener('resize', () => {
    const lists = getColumnEdgeLists();
    leftEdges = lists.leftEdges;
    rightEdges = lists.rightEdges;
  });

  // Expose API
  try {
    svg.gridLayout = svg.gridLayout || {};
    svg.gridLayout.getContents = function() {
      const contents = Array.from(svg.querySelectorAll('g[id^="content-"]'));
      return contents.map(g => {
        const r = g.querySelector('rect.content');
        const x = parseFloat(r.getAttribute('x'));
        const width = parseFloat(r.getAttribute('width'));
        return {
          id: g.id,
          x: x,
          width: width,
          columns: calculateColumnSpan(x, width)
        };
      });
    };
    svg.gridLayout.getConfig = function() {
      return {
        ...config,
        contentCount: contentCount,
        direction: currentDirection
      };
    };
  } catch(e) {}
};

// Initialization logic for ACF block preview
(() => {
  const initializedBuilders = new WeakSet();

  const initializeBlock = (block) => {
    if (!block) return;

    const gridLayoutBuilders = block.matches('.grid-layout-builder')
      ? [block]
      : Array.from(block.querySelectorAll('.grid-layout-builder'));

    gridLayoutBuilders.forEach((gridLayoutBuilder) => {
      const container = gridLayoutBuilder.querySelector('.container');
      if (!container || !gridLayoutBuilder.id) {
        return;
      }

      const blockId = gridLayoutBuilder.id.replace('grid-layout-builder-', '');
      window.initializeGridBuilder(blockId, { rootElement: gridLayoutBuilder });

      if (!initializedBuilders.has(gridLayoutBuilder)) {
        initializedBuilders.add(gridLayoutBuilder);
        disableGutenbergBlockDrag(block, gridLayoutBuilder);
      }
    });
  };

  // Disable Gutenberg block dragging when interacting with grid builder
  const disableGutenbergBlockDrag = (block, gridLayoutBuilder) => {
    // Find the ACF block wrapper
    const acfBlockPreview = block.closest('.acf-block-preview');
    const blockListBlock = block.closest('.block-editor-block-list__block');
    
    // Prevent all drag-related events on the grid builder
    const preventDrag = (e) => {
      e.stopPropagation();
      e.stopImmediatePropagation();
    };
    
    // Stop all dragging events
    block.addEventListener('mousedown', preventDrag, true);
    block.addEventListener('dragstart', preventDrag, true);
    block.addEventListener('drag', preventDrag, true);
    block.addEventListener('dragend', preventDrag, true);
    
    // Make the grid builder non-draggable
    block.setAttribute('draggable', 'false');
    
    // Prevent Gutenberg from making children draggable
    const preventChildrenDraggable = () => {
      const draggableElements = gridLayoutBuilder.querySelectorAll('[draggable="true"]');
      draggableElements.forEach(el => {
        el.setAttribute('draggable', 'false');
      });
    };
    
    preventChildrenDraggable();
    
    // Watch for changes in case Gutenberg re-adds draggable attribute
    const observer = new MutationObserver(preventChildrenDraggable);
    observer.observe(gridLayoutBuilder, {
      attributes: true,
      subtree: true,
      attributeFilter: ['draggable']
    });
    
    // If we found the block wrapper, temporarily disable its draggable state when hovering grid builder
    if (blockListBlock) {
      const disableBlockDragHandle = () => {
        const blockDragHandle = blockListBlock.querySelector('.block-editor-block-mover');
        if (blockDragHandle) {
          blockDragHandle.style.pointerEvents = 'none';
          blockDragHandle.style.opacity = '0.3';
        }
      };
      
      const enableBlockDragHandle = () => {
        const blockDragHandle = blockListBlock.querySelector('.block-editor-block-mover');
        if (blockDragHandle) {
          blockDragHandle.style.pointerEvents = '';
          blockDragHandle.style.opacity = '';
        }
      };
      
      // Disable immediately when grid builder is initialized
      disableBlockDragHandle();
      
      // Disable on mouseenter
      block.addEventListener('mouseenter', disableBlockDragHandle);
      
      // Re-enable on mouseleave
      block.addEventListener('mouseleave', enableBlockDragHandle);
      
      // Also disable immediately when grid builder is focused/clicked
      block.addEventListener('mousedown', disableBlockDragHandle);
      block.addEventListener('focus', disableBlockDragHandle);
      
      // Watch for any changes to the block drag handle and keep it disabled while interacting
      const blockDragObserver = new MutationObserver(() => {
        if (gridLayoutBuilder.matches(':hover') || gridLayoutBuilder.matches(':focus') || gridLayoutBuilder.contains(previewDocument.activeElement)) {
          disableBlockDragHandle();
        }
      });
      
      if (blockListBlock.querySelector('.block-editor-block-mover')) {
        blockDragObserver.observe(blockListBlock.querySelector('.block-editor-block-mover'), {
          attributes: true,
          attributeFilter: ['style', 'class']
        });
      }
      
      // Also watch for the drag handle being added/removed
      const blockObserver = new MutationObserver(() => {
        const dragHandle = blockListBlock.querySelector('.block-editor-block-mover');
        if (dragHandle && (gridLayoutBuilder.matches(':hover') || gridLayoutBuilder.contains(previewDocument.activeElement))) {
          disableBlockDragHandle();
        }
      });
      
      blockObserver.observe(blockListBlock, {
        childList: true,
        subtree: true
      });
    }
  };

  const initializeExistingBuilders = (root = document) => {
    const builders = root.matches && root.matches('.grid-layout-builder')
      ? [root]
      : Array.from(root.querySelectorAll ? root.querySelectorAll('.grid-layout-builder') : []);

    builders.forEach((builder) => {
      const hostBlock = builder.closest('[data-type="acf/content-block"]') || builder.parentElement || builder;
      initializeBlock(hostBlock);
    });
  };

  if (window.acf) {
    window.acf.addAction('render_block_preview/type=content-block', ($block) => {
      initializeBlock($block.get(0));
    });
  }

  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      mutation.addedNodes.forEach((node) => {
        if (!(node instanceof Element)) {
          return;
        }

        if (node.matches('.grid-layout-builder') || node.querySelector('.grid-layout-builder')) {
          initializeExistingBuilders(node);
        }
      });
    });
  });

  const startObserver = () => {
    initializeExistingBuilders(document);
    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startObserver, { once: true });
  } else {
    startObserver();
  }
})();
