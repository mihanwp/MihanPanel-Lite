jQuery(document).ready(function($){
    setTimeout(() => {
        $(document).find('input[name=is_active_2fa]').change()
    }, 100);

    $(document).on('change', 'input[name=is_active_2fa]', function (e) {
        if(e.target.checked)
        {
            $(this).closest('.two-factor-user-panel-wrapper').find('.qr-code-wrapper').slideDown();
        }else{
            $(this).closest('.two-factor-user-panel-wrapper').find('.qr-code-wrapper').slideUp();
        }
    });
});