<?php
namespace mihanpanel\app\presenter;
class admin_fields
{
    static function renderColorPickerField($value, $name, $id=null, $fieldClass='my-color-field')
    {
        ?>
        <div class="mwpl_coloris_field_wrapper">
            <input
                type="text"
                name="<?php echo esc_attr($name)?>"
                <?php echo $id ? sprintf('id="%s"', esc_attr($id)) : '';?>
                value="<?php echo esc_attr($value); ?>"
                class="<?php echo esc_attr($fieldClass)?>" />
        </div>
        <?php
    }
}