jQuery(document).ready(function ($){
    $('.mw-new-tab-record-icon').mwpl_iconpicker(".mw-new-tab-record-icon");

    $('.mwpl-icon-selector').mwpl_iconpicker(".mwpl-icon-selector-field");

    $('.fontawesome-handle').on('click', function(e){
        let mwpl_this = $(this),
            icon_field = mwpl_this.parent().find('input[name=icon]')
        mwpl_this.mwpl_iconpicker(icon_field)
        mwpl_this.focusin()
        icon_field.on('change', function(e){
            let previewIcon = $(this).parent().find('.preview_icon')
            previewIcon.html('<i class="mw_icon '+ e.target.value +'"></i>')
        })
    });

    $('.mwpl-title-tooltip').hover(function(){
        // Hover over code
        let title = $(this).attr('title');
        $(this).data('tipText', title).removeAttr('title');
        $('<div class="mwpl-tooltip"></div>').text(title).appendTo('body').fadeIn('slow');
    }, function() {
        // Hover out code
        $(this).attr('title', $(this).data('tipText'));
        $('.mwpl-tooltip').remove();
    }).mousemove(function(e) {
        let mousex = e.pageX + 20;
        let mousey = e.pageY + 10;
        $('.mwpl-tooltip').css({top: mousey, left: mousex})
    });
});