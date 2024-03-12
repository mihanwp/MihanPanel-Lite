jQuery(document).ready(function($){
    let colorPickerField = $('.my-color-field');
    Coloris({
        el: '.my-color-field',
        theme: 'polaroid',

        swatches: [
            '#264653',
            '#2a9d8f',
            '#e9c46a',
            '#EF4444',
            '#F97316',
            '#FACC15',
            '#4ADE80',
            '#2DD4BF',
            '#3B82F6',
            '#6467F2',
            '#EC4899',
            '#F43F5E',
        ],
    })
});

