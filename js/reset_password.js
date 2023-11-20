jQuery(document).ready(function($){
    $(document).on('submit', 'form.mwpl-form-wrapper.mwpl-reset-password-form', function (e) {
        e.preventDefault()
        let form = $(this),
            submitBtn = form.find('.mwpl-input-item input[type=submit]'),
            spinner = submitBtn.closest('.mwpl-input-item').find('.mwpl-spinner-wrapper'),
            stateField = form.find('.form-state:visible'),
            formState = stateField.data('state');

        emptyNotice()

        if (!formState)
            return false;

        let usernameField = form.find('input[name=username]')

        if (!(usernameField.val().length)) {
            showNotice(mwpl_data.msg.missing_username_field, 'error')
            return false
        }

        disableLabels(form)
        submitBtn.addClass('hide');
        spinner.show()

        if (formState != 'code'){

            let ajaxData = `action=mwpl_reset_password_by_verification_code&nonce=${mwpl_data.nonce}&form_state=${formState}&` + form.serialize();

            $.ajax({
                url: mwpl_data.au,
                type: 'POST',
                dataType: 'json',
                data: ajaxData,
                success: response => {
                    if (response.code == 200) {
                        // hide username field
                        usernameField.closest('.mwpl-input-item').addClass('mwpl-hide')

                        stateField.data('state', 'code')

                        // show dynamic code field
                        form.find('input[name=dynamic_code]').closest('.mwpl-input-item').removeClass('mwpl-hide')
                        form.find('input[name=dynamic_code]').focus()

                        // show timer
                        startTimer(form, response.expiration)
                        form.find('.mwpl-timer').removeClass('mwpl-hide')

                        // enable labels
                        enableLabels(form)
                        // hide spinner
                        spinner.hide()
                        // show submit button
                        submitBtn.val(mwp_data.messages.reset_password)
                        submitBtn.removeClass('hide')

                        showNotice(response.msg, 'success')

                        form.closeRecaptcha();
                        form.closeCustomCaptcha();
                    } else {
                        enableLabels(form)

                        // hide spinner
                        spinner.hide()

                        // show submit button
                        submitBtn.val(mwp_data.messages.send_code)
                        submitBtn.removeClass('hide')

                        showNotice(response.msg, 'error')
                    }
                },
                error: err => {
                    // active fields
                    enableLabels(form)

                    spinner.hide()
                    submitBtn.removeClass('hide')
                    showNotice(mwpl_data.msg.has_error_in_request, 'error')
                },
            })
        } else {
            $.ajax({
                url: mwpl_data.au,
                type: 'POST',
                dataType: 'json',
                data: `action=mwpl_verify_code_for_reset_password&nonce=${mwpl_data.nonce}&form_state=${formState}&` + form.serialize(),
                success: response => {
                    if (response.code == 200) {
                        form.find('.mwpl-timer').addClass('mwpl-hide')
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
                    } else {
                        enableLabels(form)

                        // hide spinner
                        spinner.hide()

                        // show submit button
                        submitBtn.removeClass('hide')

                        showNotice(response.msg, 'error')
                    }
                },
                error: err => {
                    // active fields
                    enableLabels(form)

                    spinner.hide()
                    submitBtn.removeClass('hide')
                    showNotice(mwpl_data.msg.has_error_in_request, 'error')
                },
            })
        }
    });
})
