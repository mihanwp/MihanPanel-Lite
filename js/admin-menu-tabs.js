jQuery(document).ready(function($){
    $('.mw-new-tab-record-icon').mwpl_iconpicker(".mw-new-tab-record-icon");
    $('.fontawesome-handle').on('click', function(e){
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
    let has_deps = $('[depends]')
    if(has_deps.length)
    {
        has_deps.each((index, item) => {
            let el = $(item),
                deps = el.attr('depends'),
                deps_el = $('#' + deps)
            if(deps_el.length)
            {
                deps_el.on('change', {item: el}, e => {
                    let mw_this = $(e.target)
                    handle_deps(mw_this, e.data.item)
                })
                handle_deps(deps_el, el)
            }
        })
    }
    function handle_deps(deps_el, item_el)
    {
        let tagName = deps_el.prop('tagName'),
            state = false

        if(tagName == 'SELECT')
        {
            let value = deps_el.find(':selected').val()
            state = value == item_el.attr('depends_value')
        }else if(tagName == 'INPUT')
        {
            let type = deps_el.attr('type')
            if(type == 'checkbox')
            {
                state = deps_el.is(":checked");
            }
        }
        if(state)
        {
            item_el.slideDown()
        }else{
            item_el.slideUp()
        }
    }
})