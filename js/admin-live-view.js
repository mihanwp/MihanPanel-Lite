jQuery(document).ready(function($){
    function show_live_view_field(e)
    {
        // hide all previous elements
        $('.mwpl_live_view_wrapper > *').hide();
        let mwpl_this = $(this),
            live_view_target_id = mwpl_this.attr('live_view_target_id'),
            live_view = $(`.mwpl_live_view_wrapper #${live_view_target_id}`)
        mwpl_this.trigger('mwpl_before_show_live_view_field', mwpl_this);
        live_view.show()
        let offset = mwpl_this.offset(),
            scrollTop = $(document).scrollTop(),
            leftPosition = (mwpl_this.outerWidth() / 2) + offset.left - (live_view.outerWidth() / 2),
            bottomPosition = window.innerHeight - (offset.top - scrollTop)
        live_view.css({bottom: bottomPosition, left: leftPosition})
    }
    function hide_live_view_field(e)
    {
        let mwpl_this = $(e.target),
            live_view_target_id = mwpl_this.attr('live_view_target_id')
            live_view = $(`.mwpl_live_view_wrapper #${live_view_target_id}`)
        live_view.hide();
    }
    $(document).on('mouseover', '[live_view_target_id]', show_live_view_field)
    $(document).on('mouseleave', '[live_view_target_id]', hide_live_view_field)
    $(document).on('mwpl_color_picker_value_changed', function(_e,el, newColor){
        let element = $(el),
            live_view_target_id = element.closest('[live_view_target_id]').attr('live_view_target_id')
        if(!live_view_target_id)
        {
            return false;
        }
        let live_view = $(`.mwpl_live_view_wrapper #${live_view_target_id}`),
            live_view_login_btn = live_view.find('.login-btn')
        live_view_login_btn.css({'background-color': newColor, 'box-shadow': `0 5px 10px ${newColor}60`})
    })
    $(document).on('mwpl_before_show_live_view_field', '[live_view_target_id=mwpl_live_view_login_button]', function(e){
        // get ui theme
        let live_view = $('.mwpl_live_view_wrapper #mwpl_live_view_login_button'),
            login_theme = $('input[name=login_form_theme]:checked').val()
        if(!login_theme)
        {
            login_theme = 'light'
        }
        live_view.removeClass('dark light')
        live_view.addClass(login_theme)
    })
    $(document).on('mwpl_before_show_live_view_field', '[live_view_target_id=mwpl_live_view_login_logo]', function(e, el){
        el = $(el)
        let live_view_target_id = el.attr('live_view_target_id'),
            live_view = $(`.mwpl_live_view_wrapper #${live_view_target_id}`)
        // get ui theme
        let login_theme = $('input[name=login_form_theme]:checked').val()
        if(!login_theme)
        {
            login_theme = 'light'
        }
        live_view.removeClass('dark light')
        live_view.addClass(login_theme)

        $('[live_view_target_id=mwpl_live_view_login_logo] .mwpl_range').each(function(index, item){
            item = $(item)
            let name = item.attr('name'),
                data = {}
            if(name === 'mp_logo_width')
            {
                data.width = item.val()
            }else{
                data.height = item.val()
            }
            live_view.find('.logo-box').css(data)
        })
    })
    $(document).on('input', '[live_view_target_id=mwpl_live_view_login_logo] .mwpl_range', function(e){
        let mwpl_this = $(this),
            live_view = $('.mwpl_live_view_wrapper #mwpl_live_view_login_logo'),
            name = mwpl_this.attr('name'),
            login_form = live_view.find('.mwpl_login_form_wrapper'),
            logo_box = login_form.find('.logo-box')
        let value = mwpl_this.val() * 100 / login_form.width()
        value = parseInt(value)
        let data = {};
        if(name === 'mp_logo_width')
        {
            data.width = mwpl_this.val()
        }else{
            data.height = mwpl_this.val()
        }
        logo_box.css(data)
    })
})