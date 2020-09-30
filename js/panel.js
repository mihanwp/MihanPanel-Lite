jQuery(document).ready(function($){
    $(document).on('click', '.mp_menu_bars', function(e){
        let mp_this = $(this),
            mp_title = mp_this.find('small');
            mp_sidebar = $(".mpsidebar"),
            main_panel = mp_sidebar.next('.main-panel');
        main_panel.toggleClass('mp_display_none');
        mp_title.toggleClass('mp_display_none');
        mp_this.toggleClass('fa-times');
        mp_this.toggleClass('fa-bars');
        mp_sidebar.toggleClass('mp_open');
    });
});