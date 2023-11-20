jQuery(document).ready(function ($){
    let body = $('body');

    body.on('mousemove', '.mwpl-range-slider-wrap input[type=range]', function (e){
        let input = $(this),
            rangeVal = input.val();
        input.parent().find('.range-value input').val(rangeVal);
    });

    body.on('input', '.mwpl-range-slider-wrap .range-value input', function (e){
        let input = $(this),
            inputVal = input.val();
        input.parent().parent().find('input[type=range]').val(inputVal);
    });

    body.on('change input', '.mwpl-range-slider-wrap select', function (e){
        let input = $(this),
            wrap = input.parent().parent(),
            rangeEl = wrap.find('input[type=range]'),
            min = rangeEl.attr('min'),
            max = rangeEl.attr('max'),
            inputVal = input.val();

        if(!rangeEl.data('min') && min) rangeEl.data('min', min);
        if(!rangeEl.data('max') && max) rangeEl.data('max', max);

        if(inputVal === '%'){
            rangeEl.attr('min', 0);
            rangeEl.attr('max', 100);
        } else {
            if(rangeEl.data('min')){
                rangeEl.attr('min', rangeEl.data('min'));
            }
            if(rangeEl.data('max')){
                rangeEl.attr('max', rangeEl.data('max'));
            }
        }
    });

    body.on('change', '.mwpl-background-control-item .background-type', function (e){
        let input = $(this),
            wrap = input.parent(),
            inputVal = input.val();

        wrap.find('.mwpl-bg-control-option:not(.all)').slideUp();
        wrap.find(`.${inputVal}-options`).slideDown();
    });

    body.on('click', '.mwpl-inputs-linked-value', function (e){
        e.preventDefault();
        let btn = $(this);
        btn.toggleClass('is-linked');
    });

    body.on('change input', '.mwpl-dimensions-wrap input', function (e){
        e.preventDefault();
        let input = $(this),
            isLinked = input.parent().find('.is-linked');
        if(isLinked.length){
            input.parent().find('input').val(input.val());
        }
    });
})
