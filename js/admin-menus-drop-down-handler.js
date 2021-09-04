jQuery(document).ready(function($){
    $('.mwpl_drop_down').on('click', function(e){
        let mw_this = $(this),
            icon = mw_this.find('span.mw_icon')
            drop_down_el_id = mw_this.attr('drop_down_target_id'),
            drop_down_el = $('#' + drop_down_el_id)
            
        drop_down_el.slideToggle()
        icon.toggleClass('show')
        if(icon.hasClass('show'))
        {
            icon.css({transform: 'rotate(180deg)'})
        }else{
            icon.css({transform: 'none'})
        }
    })
    $('.mihanpanel-admin h2 .action_btn').on('click', function(e){
        let mw_this = $(this)
        if(mw_this.hasClass('open_new_field_section'))
        {
            mw_this.closest('.fields_wrapper').slideUp()
            mw_this.closest('.mihanpanel-admin').find('.new_record_wrapper').slideDown();
        }else if(mw_this.hasClass('close_new_field_section'))
        {
            mw_this.closest('.mihanpanel-admin').find('.fields_wrapper').slideDown();
            mw_this.closest('.new_record_wrapper').slideUp();
        }
    })
})