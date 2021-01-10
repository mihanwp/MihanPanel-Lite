const {registerBlockType} = wp.blocks;

registerBlockType('mihanpanel/panel', {
    // built-in 
    title: 'Panel',
    description: 'MihanPanel panel shortcode',
    icon: 'admin-appearance',
    category: 'mihanpanel',

    edit(){
        return '[mihanpanel]'
    },
    save(){
        return '[mihanpanel]'
    }
})