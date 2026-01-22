document.addEventListener("DOMContentLoaded", () => { 
    
  const initializeBlock = (block) => {
      
    // DOM.
    const ajaxContainer = block.querySelector('.posts-archive__ajax-container');
    const itemsWrap = block.querySelector('.posts-archive__posts');
    const filters = block.querySelector('.posts-archive__taxonomy-filters');
    const pagination = block.querySelector('.posts-archive__pagination');

    if (!itemsWrap || !ajaxContainer) {
      return;
    }

    // Props.
    const cpt = ajaxContainer.dataset.cpt ? ajaxContainer.dataset.cpt.split(',') : ['post'];
    const filterByTerms = ajaxContainer.dataset.filterByTerms ? JSON.parse(ajaxContainer.dataset.filterByTerms) : null;
    const ppp = ajaxContainer.dataset.ppp;
    const includePagination = ajaxContainer.dataset.includePagination === '1' ? true : false;
    const urlBase = ajaxContainer.dataset.urlBase;
    let pageNumber = Number(ajaxContainer.dataset.paged);

    // // Check the URL for filter settings.
    // const urlParams = new URLSearchParams(window.location.search);
    // let category = urlParams.get('category') || 'all';

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

    const getTaxonomyParams = () => {
      let params = '';
      for (const [taxonomy, termIds] of Object.entries(taxonomies)) {
        if (termIds.length > 0) {
          params += `&${taxonomy}=${termIds.join(',')}`;
        }
      }
      return params;
    }

    // Store taxonomies as an object: { taxonomy_name: [term_id1, term_id2, ...], ... }
    let taxonomies = {};

    // Helper function to set term IDs for a taxonomy.
    const setTaxonomyTerms = (taxonomyName, termIds) => {
      // Convert single value to array
      const termsArray = Array.isArray(termIds) ? termIds : [termIds];
      
      // Filter out 'all' values and empty strings.
      const validTerms = termsArray.filter(id => id && id !== 'all');
      
      if (validTerms.length > 0) {
        taxonomies[taxonomyName] = validTerms;
      } else {
        // Remove taxonomy if no valid terms.
        delete taxonomies[taxonomyName];
      }
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
      formData.append( 'cpt', JSON.stringify(cpt) );
      formData.append( 'ppp', ppp );
      formData.append( 'pageNumber', pageNumber );
      formData.append( 'includePagination', includePagination ? 1 : 0 );
      formData.append( 'urlBase', urlBase );
      formData.append( 'taxonomies', JSON.stringify(taxonomies) );
      if (filterByTerms) {
        formData.append( 'filterByTerms', JSON.stringify(filterByTerms) );
      }

      fetch(ajax_params.ajaxurl, {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          
          if (!data.success) {
            console.error('AJAX request failed');
            return;
          }

          if (clearAll) {
            ajaxContainer.innerHTML = '';
          }

          if (ajaxContainer.dataset.updateUrl === '1') {
            updateUrl();
          } 

          pagination?.classList.remove('hidden');
          
          const parser = new DOMParser();
          
          // Parse posts HTML
          const postsDoc = parser.parseFromString(data.postsHtml, "text/html");
          const postsElement = postsDoc.querySelector('.posts-archive__posts');
          
          ajaxContainer.dataset.maxPages = data.maxPages;
          ajaxContainer.append(postsElement);

          // Parse and append pagination if exists
          if (data.paginationHtml) {
            const paginationDoc = parser.parseFromString(data.paginationHtml, "text/html");
            const paginationElement = paginationDoc.querySelector('.posts-archive__pagination');
            if (paginationElement) {
              ajaxContainer.append(paginationElement);
            }
          }

          // Update filters with available terms
          if (data.availableTerms && filters && filters.dataset.structure === 'multi') {
            updateFilters(data.availableTerms);
          }

          block.classList.remove('is-loading');

          const contentLoadedEvent = new CustomEvent('contentLoaded');
          document.body.dispatchEvent(contentLoadedEvent);
        
        }).catch(error => {
          console.warn(error);
          block.classList.remove('is-loading');
        });
    }

    /**
     * 
     *  Filters.
     * 
     */
    const updateFilters = (availableTerms) => {
      if (!filters) return;

      const structure = filters.dataset.structure;

      switch(structure) {
        case 'flat':
          // Update flat list - disable/enable items based on availability
          const listItems = filters.querySelectorAll('li[data-term-id][data-taxonomy]');
          
          listItems.forEach(li => {
            const termId = li.dataset.termId;
            const taxonomy = li.dataset.taxonomy;
            
            // Skip 'all' reset button
            if (termId === 'all' || taxonomy === 'all') {
              return;
            }
            
            // Check if this term is available
            const taxonomyTerms = availableTerms[taxonomy] || [];
            const isAvailable = taxonomyTerms.some(term => term.term_id == termId);
            
            if (isAvailable) {
              li.classList.remove('disabled');
              li.removeAttribute('disabled');
              const button = li.querySelector('button');
              if (button) button.disabled = false;
            } else {
              li.classList.add('disabled');
              li.setAttribute('disabled', 'disabled');
              const button = li.querySelector('button');
              if (button) button.disabled = true;
            }
          });
          break;

        case 'multi':
          // Update select dropdowns - disable options and update counts
          const selects = filters.querySelectorAll('select[data-taxonomy]');
          
          selects.forEach(select => {
            const taxonomy = select.dataset.taxonomy;
            const taxonomyTerms = availableTerms[taxonomy] || [];
            const currentValue = select.value;
            
            // Get all options except the first one (placeholder/all)
            const options = select.querySelectorAll('option[value]:not([value="all"])');
            
            options.forEach(option => {
              const termId = option.value;
              const term = taxonomyTerms.find(t => t.term_id == termId);
              
              if (term) {
                option.disabled = false;
                // Optionally update the label with count
                const baseLabel = option.textContent.replace(/\s*\(\d+\)$/, '');
                option.textContent = `${baseLabel}`; // (${term.count})`;
              } else {
                option.disabled = true;
                // Keep current selection even if disabled
                if (termId === currentValue) {
                  option.disabled = false;
                }
              }
            });
          });
          break;
      }
    }

    if (filters) {
      const structure = filters.dataset.structure;
      const resetBtn = filters.querySelector('.posts-archive__taxonomy-filters__reset');

      switch(structure) {
        
        case 'flat':
          filters.addEventListener('click', e => {
            const li = e.target.closest('li');
            li.closest('ul').querySelectorAll('li').forEach(item => item.classList.remove('active'));
            if (li) {
              e.preventDefault();
              li.classList.add('active');
              const taxonomy = li.dataset.taxonomy;
              const termId = li.dataset.termId;
              taxonomies = {};
              setTaxonomyTerms(taxonomy, termId);
              pageNumber = 1;
              loadContent(true);
            }
          });
          break;

        case 'multi':
          const selects = filters.querySelectorAll('select');
          selects.forEach(select => {
            select.addEventListener('change', e => {
              const select = e.target;
              const taxonomy = select.dataset.taxonomy;
              const termId = select.value;
              setTaxonomyTerms(taxonomy, termId);
              pageNumber = 1;
              loadContent(true);
            });
          });
          break;
      }

      resetBtn?.addEventListener('click', e => {
        e.preventDefault();
        taxonomies = {};
        pageNumber = 1;
        filters.querySelectorAll('li').forEach(item => item.classList.remove('active'));
        filters.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        loadContent(true);
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
