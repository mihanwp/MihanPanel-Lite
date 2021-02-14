jQuery(document).ready(function($){
    let colorPickerField = $('.my-color-field');
    colorPickerField.each(function(index, item){
        let el = $(item)
        el.wpColorPicker({
            defaultColor: el.attr('default_value'),
            change: function(e, ui){
                let newColor = ui.color.toString()
                $(document).trigger('mwpl_color_picker_value_changed', [this, newColor])
            }
        });
    })

});

