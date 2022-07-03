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
        let head = frame.contents().find('head')
        let style = $('<style>')
        style.text(`
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.logout-menu li.live-edit-sidebar-new-menu-item a
        {
            text-align: center;
            display: block;
            background: #fed700;
        }
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.logout-menu li.live-edit-sidebar-new-menu-item a p
        {
            color: black !important;
            font-weight: bold !important;
        }
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.logout-menu li.live-edit-sidebar-new-menu-item:hover
        {
            background-color: #fed700 !important;
        }
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li
        {
            position: relative;
        }
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li p input
        {
            all: unset !important;
        }
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li .movement-icon
        {
            position: absolute;
            opacity: 0;
            right: -10px;
            color: white;
            top: 50%;
            transform: translateY(-50%);
            background: gray;
            height: 25px;
            display: flex;
            align-items: center;
        }
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li .movement-icon::before
        {
            content: "\\f333";
            font-family: "dashicons";
        }

        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li .remove-icon
        {
            position: absolute;
            left: 5px;
            opacity: 0;
            color: white;
            height: 100%;
            background: #f72323;
            top: 0;
            display: flex;
            align-items: center;
            width: 25px;
            justify-content: center;
            border-radius: 5px;
        }
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li .remove-icon::before
        {
            content: "\\f158";
            font-family: "dashicons";
        }

        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li .edit-icon
        {
            position: absolute;
            left: 40px;
            opacity: 0;
            color: white;
            height: 100%;
            background-color: #63a0b3;
            top: 0;
            display: flex;
            align-items: center;
            width: 25px;
            justify-content: center;
            border-radius: 5px;
        }        
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li .edit-icon::before
        {
            content: "\\f464";
            font-family: "dashicons";
        }
        
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li:hover .movement-icon,
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li:hover .edit-icon,
        .mihanpanel-page .mpsidebar .nav.mp-nav-tabs.menu-tabs-items li:hover .remove-icon
        {
            opacity: 1;
        }

        .mihanpanel-page .main-panel#tab-field-content .mihanpanel-section-title .edit-content-btn
        {
            background: #63a0b3;
            color: white;
            border-radius: 5px;
            padding: 5px 15px 3px;
            font-size: 1rem;
            margin: 0 10px;
            cursor: pointer;
        }
        .mihanpanel-page #tab-field-content .edit-fields-wrapper
        {
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .mihanpanel-page #tab-field-content .edit-fields-wrapper.hide
        {
            display: none;
        }
        .mihanpanel-page #tab-field-content .edit-fields-wrapper .row-field
        {
            display: flex;
            flex-direction: column;
        }
        .mihanpanel-page #tab-field-content .edit-fields-wrapper .row-field.hide
        {
            display: none;
        }
        .mihanpanel-page #tab-field-content .edit-fields-wrapper textarea,
        .mihanpanel-page #tab-field-content .edit-fields-wrapper select,
        .mihanpanel-page #tab-field-content .edit-fields-wrapper input:not([type=submit])
        {
            width: 80%;
        }
        .mihanpanel-page #tab-field-content .edit-fields-wrapper #save_edit_field_content
        {
            display: block;
            background: #63a0b3;
            width: fit-content;
            padding: 10px 30px;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            color: white;
        }
        `)
        head.append(style)
    }
    function showNotification(msg, type='error')
    {
        alert(msg)
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
            //TODO: use notification
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