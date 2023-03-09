jQuery(document).ready(function ($) {
    $(document).on('click', '.mw_menu_icons', function(e){
        let mw_this = $(this),
            mw_sidebar = mw_this.next('.mw_sidebar');
        mw_this.toggleClass('dashicons-no');
        mw_sidebar.toggleClass('mw_open');
    });
    $('.mw_select2').select2();
    $(document).on('input', '.mwpl_range', function(e){
        let mwpl_this = $(this),
            valueWrapper = mwpl_this.parent().find('.range_value')
        valueWrapper.text(mwpl_this.val())
    });

    $(document).on("change", ".checkbox-affected-toggle-elements", function (e) {
        e.preventDefault();
        let $this = $(this),
            target = $($this.data('elements'));
        if(target.length){
            if($this.is(':checked')){
                target.slideDown();
            } else {
                target.slideUp();
            }
        }
    });

    $(document).on("change", ".radio-affected-toggle-elements", function (e) {
        e.preventDefault();
        let item = $(this),
            allTargets = $(item.data('option-targets')),
            target = $(item.data('elements'));
        if(target.length){
            if(item.is(':checked')){
                target.slideDown();
            } else {
                target.slideUp();
            }
        } else {
            if(allTargets.length){
                allTargets.slideUp();
            }
        }
    });

    $(document).on("change", ".select-affected-toggle-elements", function (e) {
        e.preventDefault();
        let item = $(this),
            itemVal = item.val(),
            allTargets = $(item.data('option-targets')),
            target = $(item.data('option-target-' + itemVal));
        if(target.length){
            if(target.is(':visible')){
                target.slideUp();
            } else {
                target.slideDown();
            }
        } else {
            if(allTargets.length){
                allTargets.slideUp();
            }
        }
    });

    $(document).on("change", ".affected-toggle-elements", function (e) {
        e.preventDefault();
        let $this = $(this),
            target = $($this.data('elements'));
        if(target.length){
            if(target.is(':visible')){
                target.slideUp();
            } else {
                target.slideDown();
            }
        }
    });
});