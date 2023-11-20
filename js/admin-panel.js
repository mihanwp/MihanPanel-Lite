jQuery(document).ready(function ($) {
    $('.mp_color_picker').wpColorPicker({
        change: function (e, ui) {
            $(e.target).val(ui.color.toString());
            $(e.target).trigger('change');
        }
    });

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

    $(document).on("change", "input[name=mp_password_type]", function (e) {
        e.preventDefault();
        let input = $(this),
            inputVal = input.val(),
            target = $('input#mp_disable_reset_password');

        if (inputVal == 'dynamic'){
            target.prop('checked', true);
            target.attr('readonly', true);
        } else {
            target.attr('readonly', false);
        }
    });

    $(document).on("change", "input[name=mp_smart_login]", function (e) {
        e.preventDefault();
        let input = $(this),
            typeEl = $('input[name=mp_password_type]:checked'),
            target = $('input#mp_disable_reset_password');

        if (input.is(':checked')){
            target.attr('readonly', false);
        } else {
            typeEl.trigger('change');
        }
    });

    $(document).on("click", ".mp-sub-tab-wrap .mp-sub-tab-item", function (e) {
        e.preventDefault();
        let btn = $(this),
            items = btn.parent().parent().find('.mp-sub-tab-item'),
            wrap = btn.closest('.mp-sub-tab-wrap'),
            tabsContent = wrap.find('.mp-sub-tab-content'),
            target = $(btn.data('target'));

        if (!target.length)
            return false

        items.removeClass('active');
        btn.addClass('active');
        tabsContent.slideUp();
        target.slideDown();
    });

    // handle guard status change
    let mwplLoginGuardStatus = $(document).find('input[name=mwpl_login_guard_status]')
    if(!mwplLoginGuardStatus.is(':checked'))
    {
        // deactivate other options
        mwplLoginGuardStatus.closest('form').find('[mwpl_depends_on=mwpl_login_guard_status]').attr('disabled', true)
    }

    $(document).on('change', 'input[name=mwpl_login_guard_status]', function(e){
        let el = $(this),
            isChecked = e.target.checked

        if(isChecked)
        {
            // activate other options
            el.closest('form').find('[mwpl_depends_on=mwpl_login_guard_status]').attr('disabled', false)
        }else{
            // deactivate other options
            el.closest('form').find('[mwpl_depends_on=mwpl_login_guard_status]').attr('disabled', true)
        }
    })
});