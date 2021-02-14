jQuery(document).ready(function ($) {
    $(document).on('click', '.mw_menu_icons', function(e){
        let mw_this = $(this),
            mw_sidebar = mw_this.next('.mw_sidebar');
        mw_this.toggleClass('dashicons-no');
        mw_sidebar.toggleClass('mw_open');
    });
    $('.mw_select2').select2();
    $(document).on('input', '.mwpl_range', function(e){
        let mwpl_this = $(this),
            valueWrapper = mwpl_this.parent().find('.range_value')
        valueWrapper.text(mwpl_this.val())
    })
});