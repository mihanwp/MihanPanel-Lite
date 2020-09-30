jQuery(document).ready(function($){
    $(".mw_do_action_btn").on('click', function(e){
        var mw_this = $(this);
        if(mw_this.attr('type') == 'button')
        {
            mw_this.attr('type', 'submit')
        }
    });
});