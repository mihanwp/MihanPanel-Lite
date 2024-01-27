jQuery(document).ready(function($){
    setTimeout(() => {
        let spinner = $(document).find(".mwp_mihanwp_feed_dashboard .mwpl-loading-spinner-wrapper")
        
        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'mwpl_get_mihanwp_rss_feed_content',
            },
            success: response => {
                if(response.code === 200)
                {
                    // hide spinner
                    spinner.removeClass('show')

                    // append items
                    let itemsWrapper = $(document).find('.mwp_mihanwp_feed_dashboard .mwpl-rss-items')
                    if(response.items && (response.items).length > 0)
                    {
                        $.each(response.items, (index, value) => {
                            let li = $('<li>'),
                                link = $('<a>')
                            link.attr('target', '_black').attr('title', value.date).attr('href', value.link).text(value.title)
                            li.append(link).append(value.description)

                            itemsWrapper.append(li)
                        })
                    }else{
                        itemsWrapper.append('<li class="empty-item">'+response.msg+'</li>')
                    }
                }
            },
            error: () => {}
        })
    }, 2000);
})