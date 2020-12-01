jQuery(document).ready(function($){

    function mw_show_update_confirm_notice()
    {
        let mw_sortable_notice = $(".mw_update_sortable_notice");
        mw_sortable_notice.slideDown();
    }
    function mw_hide_update_confirm_notice()
    {
        let mw_sortable_notice = $(".mw_update_sortable_notice");
        mw_sortable_notice.slideUp();
    }

    function mw_show_notice(mw_type, mw_message)
    {
        let mw_notice_wrapper = $('.mihanpanel-admin .mw_notice_box');
        mw_notice_wrapper.html('<p>' + mw_message + '</p>');
        let mw_class = 'notice-' + mw_type;
        mw_notice_wrapper.addClass(mw_class);
        mw_notice_wrapper.slideDown();
        setTimeout(function(){
            mw_notice_wrapper.slideUp('normal', function(){
                mw_notice_wrapper.removeClass(mw_class);
            });
        }, 3000);
    }

    $('.mw_sortable').sortable({
        cursor: "move",
        update: mw_show_update_confirm_notice,
        handle: ".dashicons-menu"
    });

    function mw_ajax_update_fields_data(mw_type, fields_data, mwpl_nonce, item)
    {
        $.ajax({
            url: mwp_data.au,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'update_' + mw_type + '_fields_data',
                fields_data: fields_data,
                mwpl_nonce: mwpl_nonce
            },
            success: function(response)
            {
                let msg_type;
                if(response.status === 200)
                {
                    msg_type = 'success';
                }else{
                    msg_type = 'error';
                }
                mw_show_notice(msg_type, response.msg);
                item.removeClass('disable');
                mw_hide_update_confirm_notice();
            }
        });
    }
    let organize_data_function = [];
    organize_data_function.tabs = function(field_data){
        let new_data = [];
        field_data.each(function(index, value){
            let item = {},
                this_value = $(value);
            item['id'] = this_value.find('input[name=id]').val();
            item['name'] = this_value.find('input[name=name]').val();
            item['link_or_content'] = this_value.find('input[name=link_or_content]').val();
            item['icon'] = this_value.find('input[name=icon]').val();
            new_data.push(item);
        });
        return new_data;
    };
    organize_data_function.user_field = function(field_data){
        let new_data = [];
        field_data.each(function(index, value){
            let this_value = $(value);
            let form = this_value.closest('form').serialize();
            new_data.push(form);
        });
        return new_data;
    };

    $(document).on('click', '.mw_ajax_update_fields_data', function(e){
        e.preventDefault();
        let mw_this = $(this),
            mw_type = mw_this.data('mw_type'),
            mwpl_nonce = mw_this.attr('mwpl_nonce');

        mw_this.addClass("disable");
        let field_data = $(document).find('.mw_sortable .mw_field_item');
        new_data = organize_data_function[mw_type](field_data);
        mw_ajax_update_fields_data(mw_type, new_data, mwpl_nonce, mw_this);
    });
    $(document).on('click', '.mw_sortable input[name=delete]', function(e){
        e.preventDefault();
        let mw_this = $(this),
            mw_type = mw_this.closest('.mw_fields_wrapper').data('mw_type');
            mw_id = mw_this.closest('.mw_field_item').find('input[name=id]').val(),
            mwpl_nonce = mw_this.closest('.mihanpanel-admin').find('.mw_ajax_update_fields_data').attr('mwpl_nonce');
        
        $.ajax({
            url: mwp_data.au,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'mw_delete_field_row',
                type: mw_type,
                mwpl_nonce: mwpl_nonce,
                id: mw_id
            },
            success: function(response){
                if(response.status == 200)
                {
                    mw_this.closest('.mw_field_item').fadeOut('slow');
                }else{
                    mw_show_notice('error', response.msg);
                }
            },
        });
    });
    $(document).on('input', '.mw_sortable input', function(e){
        mw_show_update_confirm_notice();
    });
    $(document).on('change', '.mw_sortable input', function(e){
        $(this).trigger('input');
    })
    $(document).on('change', '.mw_sortable select', function(e){
        mw_show_update_confirm_notice();
    })
    $(document).on('keypress', '.mw_sortable input', function(e){
        if(e.which == 13)
        {
            e.preventDefault();
            $('.mw_ajax_update_fields_data').trigger('click');
        }
    });
});