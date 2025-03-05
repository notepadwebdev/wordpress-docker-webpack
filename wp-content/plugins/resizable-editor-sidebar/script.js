jQuery(window).ready(function(){
    setInterval(function(){
        jQuery('.interface-interface-skeleton__sidebar').width(localStorage.getItem('toast_rs_personal_sidebar_width'))
        jQuery('.interface-interface-skeleton__sidebar').resizable({
            handles: 'w',
            resize: function(event, ui) {
                jQuery(this).css({'left': 0});
                localStorage.setItem('toast_rs_personal_sidebar_width', jQuery(this).width());
           }
        });
        
        determine_if_sidebar_open();

    }, 500);

    jQuery('body').on('click', '.interface-pinned-items button', function(){
        determine_if_sidebar_open();
    });

    function determine_if_sidebar_open(){
        var sidebar_enabled = false;
        jQuery('.interface-pinned-items button').each(function(){
            if(jQuery(this).hasClass('is-pressed')){
              sidebar_enabled = true;
            }
        })
        if(sidebar_enabled){
            jQuery('.edit-post-layout, .edit-site-layout').addClass('is-sidebar-opened');
        }else{
            jQuery('.edit-post-layout, .edit-site-layout').removeClass('is-sidebar-opened');
        }
    }

})