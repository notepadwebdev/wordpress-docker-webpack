document.addEventListener("DOMContentLoaded", () => { 
    
  const initializeBlock = (block) => {
      
    // DOM.
    const ajaxContainer = block.querySelector('.posts-archive__ajax-container');
    const itemsWrap = block.querySelector('.posts-archive__posts');
    const filters = block.querySelector('.posts-archive__filters');
    const pagination = block.querySelector('.posts-archive__pagination');

    // Props.
    // const urlBase = block.dataset.urlBase;
    const ppp = ajaxContainer.dataset.ppp;
    let urlBase = ajaxContainer.dataset.urlBase;
    let pageNumber = Number(ajaxContainer.dataset.paged);

    // Check the URL for filter settings.
    const urlParams = new URLSearchParams(window.location.search);
    let category = urlParams.get('category') || 'all';

    const getTaxonomyParams = () => {
      let params = '';
      params += `&category=${category}`;
      return params;
    }

    const updateUrl = () => {
      const url = String(document.location).split('?').shift();
      
      // Update pagination in URL if already exists.
      let pagedUrl =  url.replace(/page\/[0-9]+/, `page/${pageNumber}`);
      
      // Add pagination to URL if doesn't already exist.
      // if (!pagedUrl.includes(`page`) && pageNumber > 1) {
      //   pagedUrl += `page/${pageNumber}/`;
      // }
      
      // Add taxonomy params.
      const params = getTaxonomyParams();
      window.history.pushState({}, '', `${pagedUrl}?${params}`);
    }
    
    /**
     * 
     *  Method to load new content into the grid.
     * 
     */
    const loadContent = (clearAll) => {
      block.classList.add('is-loading');

      ajax_params.current_page = pageNumber;

      var formData = new FormData();
      formData.append( 'action', 'ajax_posts' );
      formData.append( 'ppp', ppp );
      formData.append( 'pageNumber', pageNumber );
      formData.append( 'urlBase', urlBase );
      formData.append( 'category', category );

      fetch(ajax_params.ajaxurl, {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(html => {
          
          if (clearAll) {
            ajaxContainer.innerHTML = '';
          }

          // updateUrl();

          pagination?.classList.remove('hidden');
          
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, "text/html");
          const data = doc.querySelector('.posts-archive__posts');
          const paginationLinks = doc.querySelector('.posts-archive__pagination');

          // We pass data attributes in an extra div via AJAX for pagination purposes.
          // Pick them up, apply them to the pagination button, then delete the div.
          const dataDiv = doc.querySelector('.ajax-data');
          ajaxContainer.dataset.maxPages = dataDiv.dataset.maxPages;

          ajaxContainer.append(data);

          if (paginationLinks) {
            ajaxContainer.append(paginationLinks);
          }

          block.classList.remove('is-loading');

          const contentLoadedEvent = new CustomEvent('contentLoaded');
          document.body.dispatchEvent(contentLoadedEvent);
        
        }).catch(error => {
          console.warn(error);
        });
    }

    /**
     * 
     *  Pagination.
     * 
     */
    if (pagination) {
      ajaxContainer.addEventListener('click', e => {
        const isPaginationClick = e.target.closest('.page-numbers');
        if (isPaginationClick) {
          e.preventDefault();
          const href = e.target.closest('a')?.href.split('/');
          if (href) {
            pageNumber = href[href.length - 2];
            loadContent(true);
          }
        }
      });
    }
  }

  // Initialize all block instances (Front end).
  const blocks = document.querySelectorAll('.posts-archive');
  [...blocks].forEach(block => {
    initializeBlock(block);
  });

  // Initialize block preview (CMS).
  if( window.acf ) {
    window.acf.addAction( 'render_block_preview/type=posts-archive', ($block) => {
      initializeBlock($block.get(0));
    });
  }

});
