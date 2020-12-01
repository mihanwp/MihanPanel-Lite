jQuery(document).ready(function($){
    $('.mw-new-tab-record-icon').iconpicker(".mw-new-tab-record-icon");
    $('.field-icon-col .fontawesome-handle').on('click', function(e){
        let mwpl_this = $(this),
            icon_field = mwpl_this.parent().find('input[name=icon]')
        mwpl_this.iconpicker(icon_field)
        mwpl_this.focusin()
        icon_field.on('change', function(e){
            let previewIcon = $(this).parent().find('.preview_icon')
            previewIcon.html('<i class="mw_icon '+ e.target.value +'"></i>')
        })
    })
})