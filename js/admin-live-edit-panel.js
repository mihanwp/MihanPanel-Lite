jQuery(document).ready(function ($) {
    // variables
    let iContent,
        liveEditWrapper = $(document).find('.live-edit-wrapper'),
        frame = liveEditWrapper.find('.content #live-edit-content'),
        nonce = liveEditWrapper.attr('mwpl-nonce')

    // functions
    
    function disableMenuItems() {
        let menuWrapper = iContent.find('.mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items')
        menuWrapper.css({ 'pointer-events': 'none', 'opacity': '.5' })
    }
    function enableMenuItems() {
        let menuWrapper = iContent.find('.mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items')
        menuWrapper.css({ 'pointer-events': '', 'opacity': '' })
    }
    function enableIframeLoadingMode() {
        liveEditWrapper.find('.content').addClass('preload-mode')
    }
    function disabledIframeLoadingMode() {
        liveEditWrapper.find('.content').removeClass('preload-mode')
    }

    function renderMenuItems() {
        let menuWrapper = iContent.find('.mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items')
        disableMenuItems()
        $.ajax({
            url: mwp_data_lite.au,
            type: 'post',
            dataType: 'json',
            data: {
                mwpl_nonce: nonce,
                action: 'mwpl_live_edit_tabs_fields_get_items'
            },
            success: response => {
                if (response.status === 200) {
                    menuWrapper.html(response.data)
                }
                enableMenuItems()
            },
            error: err => {
                enableMenuItems()
            }
        })
    }
    function addNewItemBtnInMenu() {
        let logoutMenuWrapper = iContent.find('.mihanpanel-page .mpsidebar .nav.mp-nav-tabs.logout-menu')
        let newItem = $('<li>'),
            newItemName = mwp_data_lite.texts.new_item
        newItem.html(`<a><p>${newItemName}</p></a>`)
        newItem.addClass('live-edit-sidebar-new-menu-item pro-version-notice-emmit')
        logoutMenuWrapper.prepend(newItem)
    }

    function addEditContentBtn()
    {
        let title = iContent.find('.mihanpanel-page .main-panel#tab-field-content .mihanpanel-section-title')
        if(!title.length)
        {
            return
        }
        let btn = $('<span>')
        btn.addClass('edit-content-btn pro-version-notice-emmit')
        btn.text(mwp_data_lite.texts.edit_content_btn_text)
        title.append(btn)
    }

    function overrideIframeStyle() {
        let head = frame.contents().find('head'),
            styles = mwp_data_lite.assets !== undefined && mwp_data_lite.assets.styles !== undefined ? mwp_data_lite.assets.styles : false
        if(typeof styles !== 'object' || !Object.keys(styles).length)
        {
            return;
        }
        Object.values(styles).forEach((value, index) => {
            link = $('<link>'),
            link.attr('rel', 'stylesheet')
                .attr('href', value)
            head.append(link)
        })
    }
    function showNotification(msg, type='error')
    {
        let notificationWrapper = liveEditWrapper.find('.notification-wrapper'),
            notificationItem = $('<div>')
        notificationItem.addClass('notice-item')
        notificationItem.attr('type', type)
        notificationItem.text(msg)
        notificationWrapper.append(notificationItem)
        setTimeout(() => {
            notificationItem.remove()
        }, 3000);
    }
    // events
    frame.on('load', function () {
        disabledIframeLoadingMode()
        iContent = frame.contents().find('body')
        renderMenuItems()
        overrideIframeStyle()
        addNewItemBtnInMenu()
        addEditContentBtn()
        
        iContent.on('click', '.mihanpanel-page .pro-version-notice-emmit', function(e){
            e.preventDefault()
            e.stopPropagation()
            //use notification
            showNotification(mwp_data_lite.texts.pro_version)
        })
        
        iContent.find('.mihanpanel-page a').on('click', function (e) {
            e.preventDefault()
            // e.stopPropagation()
        })

        iContent.on('click', '.mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li .edit-icon', function (e) {
            e.preventDefault()
            e.stopPropagation()
            let mwthis = $(this),
                link = mwthis.closest('li').find('a').attr('mwpl-href')
            if (link.length > 0) {
                enableIframeLoadingMode()
                frame.attr('src', link)
                frame.attr('mwpl_current_tab', mwthis.closest('li').attr('tab-id'))
            }
        })
    })
})