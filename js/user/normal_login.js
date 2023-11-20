jQuery(document).ready(function ($) {

    // functions

    // events
    $(document).on('submit', 'form.mwpl-form-wrapper.mwpl-login-form', function (e) {
        e.preventDefault()
        let form = $(this),
            submitBtn = form.find('.mwpl-input-item input[type=submit]'),
            spinner = submitBtn.closest('.mwpl-input-item').find('.mwpl-spinner-wrapper'),
            recaptchaInput = form.find('.g-recaptcha-response');

        let usernameField = form.find('input[name=username]'),
            passwordField = form.find('input[name=password]')

        if (!(usernameField.val().length)) {
            showNotice(mwpl_data.msg.missing_username_field_value, 'error')
            return false
        }
        if (!(passwordField.val().length)) {
            showNotice(mwpl_data.msg.missing_password_field_value, 'error')
            return false
        }

        disableLabels(form)

        submitBtn.addClass('hide');
        spinner.show()

        let ajaxData = `action=mwpl_login_form_normal_login&nonce=${mwpl_data.nonce}&` + form.serialize();

        if (form.find('.mwpl-2fa-field-wrapper').is(':visible') && recaptchaInput.attr('type') === 'hidden'){
            ajaxData += '&grr_reject=true';
        }

        $.ajax({
            url: mwpl_data.au,
            type: 'post',
            dataType: 'json',
            data: ajaxData,
            success: response => {
                if (response.code == 200) {
                    // check 2fa state
                    if(response.handle_2fa)
                    {
                        showNotice(response.msg, 'success')

                        // deactivate all fields
                        // usernameField.closest('label').addClass('disabled')
                        // passwordField.closest('label').addClass('disabled')

                        // show 2fa field
                        form.find('.mwpl-2fa-field-wrapper').removeClass('mwpl-hide').find('label').removeClass('disabled')

                        // show button
                        spinner.hide()

                        // hide spinner
                        submitBtn.removeClass('hide')

                        let otpHiddenField = form.find('input[name=otp_active]')
                        if(otpHiddenField.length)
                        {
                            otpHiddenField.val('1')
                        }else{
                            otpHiddenField = $('<input>')
                            otpHiddenField.attr('name', 'otp_active')
                            otpHiddenField.attr('type', 'hidden')
                            otpHiddenField.attr('value', '1')
                            form.append(otpHiddenField)
                        }
                        

                    }else{
                        // redirect user
                        showNotice(response.msg, 'success')
                    
                        let queryString = window.location.search,
                            params = new URLSearchParams(queryString),
                            redirect_to = params.get('redirect_to')
    
                        redirect_to = redirect_to ? redirect_to : (response.redirect_to ? response.redirect_to : false)
    
                        if (redirect_to) {
                            setTimeout(() => {
                                window.location.href = redirect_to
                            }, 200);
                        }
                    }
                } else {
                    // active fields
                    enableLabels(form)

                    // show msg
                    showNotice(response.msg, 'error')

                    // show button
                    spinner.hide()
                    submitBtn.removeClass('hide')
                    if (typeof resetGoogleRecaptcha === 'function'){
                        resetGoogleRecaptcha();
                    }
                }
            },
            error: err => {
                // active fields
                enableLabels(form)
                
                spinner.hide()
                submitBtn.removeClass('hide')
                showNotice(mwpl_data.msg.has_error_in_request, 'error')
                if (typeof resetGoogleRecaptcha === 'function'){
                    resetGoogleRecaptcha();
                }
            },
            complete: () => {
                // spinner.hide()
                // submitBtn.removeClass('hide')
            },
        })

    });
})