jQuery(document).ready(function ($) {
    $(document).on('click', '.mw_menu_icons', function(e){
        let mw_this = $(this),
            mw_sidebar = mw_this.next('.mw_sidebar');
        mw_this.toggleClass('dashicons-no');
        mw_sidebar.toggleClass('mw_open');
    });
    $('.mw_select2').select2();
});