jQuery(document).ready(function ($) {
    // functions
    window.showNotice = function (msg, type = 'error') {
        if (!msg) {
            return
        }
        let notice = $(document).find('.mwpl-login-wrapper .mwpl-notice-wrapper .mwpl-notice');
        notice.fadeOut('fast', function () {
            notice.text(msg)
            notice.attr('class', 'mwpl-notice mwpl-' + type)
            notice.fadeIn();
        })

    }
    window.emptyNotice = function () {
        let notice = $(document).find('.mwpl-login-wrapper .mwpl-notice-wrapper .mwpl-notice');
        notice.fadeOut('fast', function () {
            notice.text('')
            notice.attr('class', 'mwpl-notice')
        })
    }

    window.disableLabels = function (form) {
        let labels = form.find('label')
        labels.each((index, value) => {
            $(value).addClass('disabled')
        })
    }
    window.enableLabels = function (form) {
        let labels = form.find('label')
        labels.each((index, value) => {
            $(value).removeClass('disabled')
        })
    }


    // events
    $(document).find('.mwpl-login-wrapper input[type=text], .mwpl-login-wrapper input[type=email], .mwpl-login-wrapper input[type=password]').val('')
    $(document).on('input', '.mwpl-login-wrapper input[type=text], .mwpl-login-wrapper input[type=email], .mwpl-login-wrapper input[type=password]', function (e) {
        if (e.target.value.length > 0) {
            $(this).addClass('mwpl-has-value')
        } else {
            $(this).removeClass('mwpl-has-value')
        }
    })
    $(document).on('click', '.mwpl-login-body .mwpl-form-fields .mwpl-input-item .mwpl-action-icon', function (e) {
        let el = $(this),
            action = el.attr('mwpl-action')

        if (action == 'show') {
            // show password value
            el.closest('.mwpl-input-item').find('input[type=password]').attr('type', 'text')

            // change icon
            el.find('img[mwpl-action=show-pass]').hide()
            el.find('img[mwpl-action=hide-pass]').show()

            // change state
            el.attr('mwpl-action', 'hide')

        } else {
            // hide password value
            el.closest('.mwpl-input-item').find('input[type=text]').attr('type', 'password')

            // change icon
            el.find('img[mwpl-action=hide-pass]').hide()
            el.find('img[mwpl-action=show-pass]').show()

            // change state
            el.attr('mwpl-action', 'show')
        }
    })

    $(document).on('click', 'form.mwpl-form-wrapper .resetpassword-btn', function (e) {
        e.preventDefault()
        let el = $(this),
            loginForm = el.closest('form.mwpl-form-wrapper'),
            resetPassForm = el.closest('.mwpl-login-wrapper').find('form.mwpl-reset-password-form')

        emptyNotice()
        loginForm.fadeOut('fast', function () {
            resetPassForm.fadeIn('slow')
        })
    })

    $(document).on('click', 'form.mwpl-form-wrapper .back, form.mwpl-form-wrapper span[mwpl-action="back"]', function (e) {
        e.preventDefault();
        openEventListener(this, "mwpl_form_back_button");
    })

    // $(document).on('submit', 'form.mwpl-form-wrapper', function(e){
    //     e.preventDefault()
    //     let el = $(this).find('.mwpl-input-item input[type=submit]'),
    //         spinner = el.closest('.mwpl-input-item').find('.mwpl-spinner-wrapper')
    //     let labels = $(this).find('label')
    //     labels.each((index, value) => {
    //         $(value).addClass('disabled')
    //     })

    //     el.addClass('hide');
    //     spinner.show()
    //     setTimeout(() => {
    //         // spinner.hide()
    //         // el.removeClass('hide')
    //         el.attr('disabled', true)
    //         showNotice('در حال انتقال به پنل مدیریت', 'error')
    //     }, 2000);
    // });
})

