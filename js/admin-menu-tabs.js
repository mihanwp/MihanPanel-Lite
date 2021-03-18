jQuery(document).ready(function($){
    $('.mw-new-tab-record-icon').mwpl_iconpicker(".mw-new-tab-record-icon");
    $('.field-icon-col .fontawesome-handle').on('click', function(e){
        let mwpl_this = $(this),
            icon_field = mwpl_this.parent().find('input[name=icon]')
        mwpl_this.mwpl_iconpicker(icon_field)
        mwpl_this.focusin()
        icon_field.on('change', function(e){
            let previewIcon = $(this).parent().find('.preview_icon')
            previewIcon.html('<i class="mw_icon '+ e.target.value +'"></i>')
        })
    })
    $('.mihanpanel-admin .mihanmenustable #iscontent').on('change', function(e){
        let mwpl_this = $(this),
            value = mwpl_this.val()
        let custom_shortcode_field = mwpl_this.closest('td').find('#custom_shortcode_value')
        if(value === 'mwp_custom_shortcode')
        {
            custom_shortcode_field.attr('name', mwpl_this.attr('name'))
            mwpl_this.attr('name', null)
            custom_shortcode_field.show()
        }else{
            mwpl_this.attr('name', custom_shortcode_field.attr('name'))
            custom_shortcode_field.attr('name', null)
            custom_shortcode_field.hide()
        }
    })
})