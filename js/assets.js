let timer

function getCurrentUtcDate() {
    let currentLocal = new Date(),
        currentTime = new Date(
            currentLocal.getUTCFullYear(),
            currentLocal.getUTCMonth(),
            currentLocal.getUTCDate(),
            currentLocal.getUTCHours(),
            currentLocal.getUTCMinutes(),
            currentLocal.getSeconds()
        );
    return currentTime;
}

function startTimer(form, expirationTime) {
    let timerEl = form.find('.mwpl-timer'),
        hourMiliseconds = 60 * 60 * 1000,
        minuteMiliseconds = 60 * 1000;

    if (!expirationTime) {
        expirationTime = timerEl.attr('mwpl-exp')
    }
    expirationTime = expirationTime ? new Date(expirationTime).getTime() : false
    if (!expirationTime) {
        return false
    }

    timer = setInterval(() => {
        let current_time = getCurrentUtcDate().getTime(),
            distance = parseInt(expirationTime) - parseInt(current_time);
        let minutes = Math.floor((distance % hourMiliseconds) / minuteMiliseconds),
            seconds = Math.floor((distance % minuteMiliseconds) / 1000);

        if (minutes.toString().length == 1) {
            minutes = '0' + minutes.toString()
        }
        if (seconds.toString().length == 1) {
            seconds = '0' + seconds.toString()
        }
        timerEl.text(`${minutes}:${seconds}`)

        if (distance <= 0) {
            clearInterval(timer)
            timerEl.text('00:00')
        }
    }, 1000);
}

const openEventListener = function (target, eventType){
    let openEvent = new Event(eventType);
    target.dispatchEvent(openEvent);
}

jQuery.fn.getRecaptchaToken = function (){
    let token = this.find('#g-recaptcha-response').val();
    return token ? token : false;
}

jQuery.fn.toggleRecaptcha = function (){
    this.find('.agr-recaptcha-wrapper').slideToggle();
}

jQuery.fn.openRecaptcha = function (){
    this.find('.agr-recaptcha-wrapper').slideDown("fast", function (){
        openEventListener(this, "mwpl_open_recaptcha")
    });
}

jQuery.fn.closeRecaptcha = function (){
    this.find('.agr-recaptcha-wrapper').slideUp("fast", function (){
        openEventListener(this, "mwpl_close_recaptcha")
    });
}

jQuery.fn.openCustomCaptcha = function (){
    this.find('.captcha-field-group').slideDown("fast", function (){
        openEventListener(this, "mwpl_open_custom_captcha")
    });
}

jQuery.fn.closeCustomCaptcha = function (){
    this.find('.captcha-field-group').slideUp("fast", function (){
        openEventListener(this, "mwpl_close_custom_captcha")
    });
}

jQuery(document).on('click', 'form.mwpl-form-wrapper .back, form.mwpl-form-wrapper span[mwpl-action="back"], form.mwpl-form-wrapper .mwpl-back-to-login', function (e) {
    let form = jQuery(this).closest('form');
    form.openCustomCaptcha();
    form.openRecaptcha();
})