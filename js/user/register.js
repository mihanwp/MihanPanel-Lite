jQuery(document).ready(function ($) {

    // variables

    // functions
    // Function to check password strength
    // return 1 for weak password
    // return 2 for moderate password
    // return 3 for strong password
    function checkPasswordStrength(password) {
        // Define the criteria for a strong password
        var minLength = 8;
        var hasLowerCase = /[a-z]/.test(password);
        var hasUpperCase = /[A-Z]/.test(password);
        var hasNumber = /\d/.test(password);
        var hasSpecialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(password);

        // Calculate the strength based on the criteria
        var strength = 0;
        if (password.length >= minLength) {
            strength += 1;
        }
        if (hasLowerCase) {
            strength += 1;
        }
        if (hasUpperCase) {
            strength += 1;
        }
        if (hasNumber) {
            strength += 1;
        }
        if (hasSpecialChar) {
            strength += 1;
        }

        // Determine the password strength level
        if (strength < 3) {
            return 1
        } else if (strength < 4) {
            return 2
        } else {
            return 3
        }
    }

    // events
    $(document).on('click', 'form.mwpl-form-wrapper .mwpl-register-link', function (e) {
        e.preventDefault()
        let el = $(this),
            loginForm = el.closest('form.mwpl-form-wrapper'),
            registerForm = el.closest('.mwpl-login-wrapper').find('form.mwpl-register-form-wrapper')

        emptyNotice()
        loginForm.fadeOut('fast', function () {
            registerForm.fadeIn('slow')
        })
    })
    $(document).on('click', 'form.mwpl-form-wrapper .mwpl-back-to-login', function (e) {
        e.preventDefault()
        let el = $(this),
            registerForm = el.closest('form.mwpl-form-wrapper'),
            loginForm = el.closest('.mwpl-login-wrapper').find('form.mwpl-login-form')

        emptyNotice()
        registerForm.fadeOut('fast', function () {
            loginForm.fadeIn('slow')
        })

        openEventListener(this, "mwpl_back_to_login");
    })

    // check password strength
    $(document).on('input', 'form.mwpl-form-wrapper.mwpl-register-form-wrapper input[name=password]', function (e) {
        let el = $(this),
            passwordStrength = checkPasswordStrength(el.val()),
            passwordStrengthField = el.closest('.mwpl-input-item').find('.mwpl-password-strength')

        let passwordStrengthMode,
            passwordStrengthFieldClass

        if (passwordStrength == 1) {
            // weak password
            passwordStrengthMode = 'weak'

        } else if (passwordStrength == 2) {
            // moderate password
            passwordStrengthMode = 'moderate'
        } else if (passwordStrength == 3) {
            // strong password
            passwordStrengthMode = 'strong'
        }
        passwordStrengthFieldClass = `mwpl-${passwordStrengthMode}-mode`

        if(!passwordStrengthField.hasClass(passwordStrengthFieldClass))
        {
            passwordStrengthField.fadeOut('fast', function(){
                passwordStrengthField.text(passwordStrengthField.attr(`mwpl-${passwordStrengthMode}-mode-text`))
                passwordStrengthField.attr('class', `mwpl-password-strength ${passwordStrengthFieldClass}`)

                passwordStrengthField.fadeIn()
                el.closest('.mwpl-password-field-wrapper').attr('mwpl-password-strength-mode', passwordStrengthMode)
            })
        }
    })

    $(document).on('submit', 'form.mwpl-form-wrapper.mwpl-register-form-wrapper', function (e) {
        e.preventDefault()
        let form = $(this),
            submitBtn = form.find('.mwpl-input-item input[type=submit]'),
            spinner = submitBtn.closest('.mwpl-input-item').find('.mwpl-spinner-wrapper');

        disableLabels(form)
        submitBtn.addClass('hide')
        spinner.show()

        let formData = new FormData(this)
        formData.append('action', 'mwpl_register_form')
        formData.append('nonce', mwpl_data_register.nonce)

        // get current redirect to from query string
        let queryString = window.location.search,
                params = new URLSearchParams(queryString),
                redirect_to = params.get('redirect_to')
        if(redirect_to)
        {
            formData.append('redirect_to_value', redirect_to)
        }

        $.ajax({
            url: mwpl_data_register.au,
            type: 'post',
            dataType: 'json',
            contentType: false,
            processData: false,
            data: formData,
            success: response => {
                if (response.code == 200) {
                    // redirect user
                    showNotice(response.msg, 'success')
                    $('.agr-recaptcha-wrapper').slideUp();
                    if(response.redirect_data)
                    {
                        let newUrlData = new URLSearchParams()
                        let newUrl = response.redirect_data.redirect_to_url
                        if(response.redirect_data.params)
                        {
                            for(let index in response.redirect_data.params)
                            {
                                newUrlData.append(index, response.redirect_data.params[index])
                            }
                            newUrl = newUrl + '?' + newUrlData.toString()
                        }
                        setTimeout(() => {
                            window.location.href = newUrl
                        }, 200);
                    }
                } else {
                    // active fields
                    enableLabels(form)

                    // show msg
                    showNotice(response.msg, 'error')

                    // show button
                    spinner.hide()
                    submitBtn.removeClass('hide')
                    if (typeof resetGoogleRecaptcha === 'function') {
                        resetGoogleRecaptcha();
                    }
                }
            },
            error: err => {
                // active fields
                enableLabels(form)

                spinner.hide()
                submitBtn.removeClass('hide')
                showNotice(mwpl_data_register.msg.has_error_in_request, 'error')
                if (typeof resetGoogleRecaptcha === 'function') {
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