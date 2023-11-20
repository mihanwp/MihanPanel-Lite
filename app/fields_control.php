<?php
namespace mihanpanel\app;

class fields_control
{
    const SELECT = 1;
    const RANGE = 2;
    const DIMENSIONS = 3;
    const COLOR = 4;
    const BACKGROUND_GROUP = 5;
    const CHECKBOX = 6;
    const ICON_UPLOADER = 7;
    const FILE_UPLOADER = 8;

    public static function renderControl($type, $params = []){
        switch ($type){
            case self::SELECT:
                self::renderSelectControl($params);
                break;
            case self::RANGE:
                self::renderRangeControl($params);
                break;
            case self::DIMENSIONS:
                self::renderDimensionsControl($params);
                break;
            case self::COLOR:
                self::renderColorControl($params);
                break;
            case self::BACKGROUND_GROUP:
                self::renderBackgroundGroupControl($params);
                break;
            case self::CHECKBOX:
                self::renderCheckbox($params);
                break;
            case self::ICON_UPLOADER:
                self::renderIconUploader($params);
                break;
            case self::FILE_UPLOADER:
                self::renderFileUploader($params);
                break;
        }
    }

    protected static function renderInputAtts($atts){
        $str = '';
        if($atts){
            foreach ($atts as $key => $value){
                $str .= sprintf('%s="%s" ', $key, esc_attr($value));
            }
        }
        echo $str;
    }

    /**
     * @param $params
     * @return void
     */
    protected static function renderIconUploader($params = [])
    {
        $label = isset($params['label']) ? $params['label'] : esc_html__('Icon', 'mihanpanel');
        $placeholder = isset($params['placeholder']) ? $params['placeholder'] : '';
        $id = isset($params['id']) ? $params['id'] : 'field_icon';
        $name = isset($params['name']) ? $params['name'] : $id;
        $value = isset($params['value']) ? $params['value'] : '';
        ?>
        <label for="<?php echo esc_attr($id) ?>"><?php echo $label ?></label>
        <div class="mwpl-icon-upload-field-wrap" data-type="icon-uploader">
            <input type="text" value="<?php echo esc_attr($value) ?>" id="<?php echo esc_attr($id) ?>" class="mw-field-record-icon mwpl-icon-selector-field" name="<?php echo esc_attr($name) ?>" placeholder="<?php echo esc_attr($placeholder); ?>"/>
            <span class="mwpl-upload-icon-btn mwpl-icon-selector icon-selector-btn mwpl-title-tooltip" title="<?php echo esc_attr__('Click for select icon', 'mihanpanel') ?>"><i class="dashicons dashicons-screenoptions mw_icon"></i></span>
            <span class="mwpl-upload-icon-btn mwpl-upload-button mwpl-title-tooltip" data-value-type="url" title="<?php echo esc_attr__('Click for upload icon', 'mihanpanel') ?>"><i class="dashicons dashicons-upload mw_icon"></i></span>
        </div>
        <?php
    }

    /**
     * @param $params
     * @return void
     */
    protected static function renderFileUploader($params = [])
    {
        $label = isset($params['label']) ? $params['label'] : esc_html__('File', 'mihanpanel');
        $placeholder = isset($params['placeholder']) ? $params['placeholder'] : esc_attr__('Enter a file of your choice or address', 'mihanpanel');
        $id = isset($params['id']) ? $params['id'] : 'field_icon';
        $name = isset($params['name']) ? $params['name'] : $id;
        $value = isset($params['value']) ? $params['value'] : '';
        $description = isset($params['description']) ? $params['description'] : '';
        $file_types = isset($params['types']) ? $params['types'] : '';
        $allowed_upload = isset($params['allowed_upload']) ? $params['allowed_upload'] : true;
        ?>
        <div class="uploader-section">
            <label><?php echo esc_attr($label) ?></label>
            <div class="mwpl-image-selector">
                <input type="text" name="<?php echo esc_attr($name) ?>" dir="ltr" value="<?php echo esc_attr($value) ?>" placeholder="<?php echo esc_attr($placeholder) ?>" data-setting="file_uploader">
                <?php if ($allowed_upload): ?>
                    <button type="button" class="mwpl-upload-button" data-type="<?php echo esc_attr($file_types) ?>" data-value-type="url"><?php echo __('Select', 'mihanpanel') ?></button>
                <?php endif; ?>
            </div>
            <?php if (!empty($description)): ?>
            <p class="description"><?php echo $description ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * @param array $params
     * @return void
     */
    protected static function renderSelectControl(array $params){
        $field_name = $params['name'];
        $id = isset($params['id']) && !empty($params['id']) ? $params['id'] : $field_name;
        $options = isset($params['options']) ? $params['options'] : null;
        $selected = isset($params['selected']) ? $params['selected'] : '';
        $class = isset($params['class']) ? $params['class'] : '';
        $multiple = isset($params['multiple']) && $params['multiple'] === true;
        $input_atts = isset($params['input_atts']) && is_array($params['input_atts']) ? $params['input_atts'] : null;
        ?>
        <div class="mwpl-content-control select-control-item mp-control-item" data-type="select">
            <?php if (isset($params['label']) && !empty($params['label'])): ?>
                <label for="<?php echo esc_attr($id) ?>"><?php echo $params['label'] ?></label>
            <?php endif; ?>
            <?php if ($options): ?>
                <select name="<?php echo esc_attr($field_name) ?>" id="<?php echo esc_attr($id) ?>" <?php self::renderInputAtts($input_atts) ?> class="<?php echo esc_attr($class) ?>" <?php echo $multiple ? 'multiple' : '' ?>>
                    <?php foreach ($options as $key => $value): ?>
                        <option <?php echo is_array($selected) ? (in_array($key,$selected) ? 'selected' : '') : selected($selected, $key, false) ?> value="<?php echo esc_attr($key) ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
    <?php }

    /**
     * @param $params array
     * @return void
     */
    protected static function renderRangeControl(array $params){
        $field_name = $params['name'];
        $current_unit = isset($params['current_unit']) ? $params['current_unit'] : 'px';
        $min = isset($params['min']) ? sprintf('min="%d"', ($current_unit === '%' ? '0' : $params['min'])) : '';
        $max = isset($params['max']) ? sprintf('max="%d"', ($current_unit === '%' ? '100' : $params['max'])) : '';
        $step = isset($params['step']) ? sprintf('step="%d"', $params['step']) : '';
        $current_value = isset($params['value']) && !empty($params['value']) ? $params['value'] : intval($min);
        $units = isset($params['units']) && is_array($params['units']) ? $params['units'] : '';
        $is_inner = isset($params['is_inner']) && $params['is_inner'] == true;
        $input_atts = isset($params['input_atts']) && is_array($params['input_atts']) ? $params['input_atts'] : null;
        $class = isset($params['class']) && !empty($params['class']) ? $params['class'] : '';
        ?>
        <div class="<?php echo !$is_inner ? 'mwpl-style-control' : '' ?> range-control-item mp-control-item <?php echo $class ?>" data-type="range" data-selectors-json="<?php echo isset($params['selectors']) ? esc_attr(json_encode($params['selectors'])) : '' ?>">
            <?php if (isset($params['label']) && !empty($params['label'])): ?>
                <label for="<?php echo esc_attr($field_name) ?>"><?php echo $params['label'] ?></label>
            <?php endif; ?>
            <div class="mwpl-range-slider-wrap">
                <div class="unit">
                    <select name="<?php echo esc_attr($field_name) ?>_unit" id="<?php echo esc_attr($field_name) ?>_unit" <?php self::renderInputAtts($input_atts) ?>>
                        <?php if ($units): ?>
                            <?php foreach ($units as $unit): ?>
                                <option <?php selected($current_unit, $unit) ?> value="<?php echo esc_attr($unit) ?>"><?php echo strtoupper($unit) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option <?php selected($current_unit, '%') ?> value="%">%</option>
                            <option <?php selected($current_unit, 'px') ?> value="px">PX</option>
                            <option <?php selected($current_unit, 'rem') ?> value="rem">REM</option>
                            <option <?php selected($current_unit, 'em') ?> value="em">EM</option>
                        <?php endif; ?>
                    </select>
                </div>
                <input type="range" name="<?php echo esc_attr($field_name) ?>" <?php echo $min ?> <?php echo $max ?> <?php echo $step ?> data-min="<?php echo isset($params['min']) ? $params['min'] : '' ?>" data-max="<?php echo isset($params['max']) ? $params['max'] : '' ?>" value="<?php echo intval(esc_attr($current_value)) ?>">
                <div class="range-value"><input type="number" value="<?php echo intval(esc_attr($current_value)) ?>"></div>
            </div>
        </div>
    <?php }

    protected static function renderDimensionsControl(array $params){
        $field_name = $params['name'];
        $current_unit = isset($params['current_unit']) ? $params['current_unit'] : 'px';
        $min = isset($params['min']) ? sprintf('min="%d"', ($current_unit === '%' ? '0' : $params['min'])) : '';
        $max = isset($params['max']) ? sprintf('max="%d"', ($current_unit === '%' ? '100' : $params['max'])) : '';
        $step = isset($params['step']) ? sprintf('step="%d"', $params['step']) : '';
        $current_value = isset($params['values']) && !empty($params['values']) ? $params['values'] : null;
        $units = isset($params['units']) && is_array($params['units']) ? $params['units'] : '';
        $is_inner = isset($params['is_inner']) && $params['is_inner'] == true;
        $input_atts = isset($params['input_atts']) && is_array($params['input_atts']) ? $params['input_atts'] : null;
        $is_linked = isset($params['is_linked']) && $params['is_linked'] === true;
        ?>
        <div class="<?php echo !$is_inner ? 'mwpl-style-control' : '' ?> dimensions-control-item mp-control-item" data-type="dimensions" data-selectors-json="<?php echo isset($params['selectors']) ? esc_attr(json_encode($params['selectors'])) : '' ?>">
            <?php if (isset($params['label']) && !empty($params['label'])): ?>
                <label for="<?php echo esc_attr($field_name) ?>"><?php echo $params['label'] ?></label>
            <?php endif; ?>
            <div class="mwpl-dimensions-wrap">
                <div class="unit">
                    <select name="<?php echo esc_attr($field_name) ?>_unit" id="<?php echo esc_attr($field_name) ?>_unit">
                        <?php if ($units): ?>
                            <?php foreach ($units as $unit): ?>
                                <option <?php selected($current_unit, $unit) ?> value="<?php echo esc_attr($unit) ?>"><?php echo strtoupper($unit) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option <?php selected($current_unit, '%') ?> value="%">%</option>
                            <option <?php selected($current_unit, 'px') ?> value="px">PX</option>
                            <option <?php selected($current_unit, 'rem') ?> value="rem">REM</option>
                            <option <?php selected($current_unit, 'em') ?> value="em">EM</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mwpl-inputs-linked-value <?php echo $is_linked ? 'is-linked' : '' ?>">
                    <i class="dashicons dashicons-admin-links"></i>
                </div>
                <input type="number" name="<?php echo esc_attr($field_name) ?>_top" <?php echo $min ?> <?php echo $max ?> <?php echo $step ?> data-min="<?php echo isset($params['min']) ? $params['min'] : '' ?>" data-max="<?php echo isset($params['max']) ? $params['max'] : '' ?>" <?php self::renderInputAtts($input_atts) ?> value="<?php echo $current_value ? esc_attr($current_value['top']) : '' ?>">
                <input type="number" name="<?php echo esc_attr($field_name) ?>_right" <?php echo $min ?> <?php echo $max ?> <?php echo $step ?> data-min="<?php echo isset($params['min']) ? $params['min'] : '' ?>" data-max="<?php echo isset($params['max']) ? $params['max'] : '' ?>" <?php self::renderInputAtts($input_atts) ?> value="<?php echo $current_value ? esc_attr($current_value['right']) : '' ?>">
                <input type="number" name="<?php echo esc_attr($field_name) ?>_bottom" <?php echo $min ?> <?php echo $max ?> <?php echo $step ?> data-min="<?php echo isset($params['min']) ? $params['min'] : '' ?>" data-max="<?php echo isset($params['max']) ? $params['max'] : '' ?>" <?php self::renderInputAtts($input_atts) ?> value="<?php echo $current_value ? esc_attr($current_value['bottom']) : '' ?>">
                <input type="number" name="<?php echo esc_attr($field_name) ?>_left" <?php echo $min ?> <?php echo $max ?> <?php echo $step ?> data-min="<?php echo isset($params['min']) ? $params['min'] : '' ?>" data-max="<?php echo isset($params['max']) ? $params['max'] : '' ?>" <?php self::renderInputAtts($input_atts) ?> value="<?php echo $current_value ? esc_attr($current_value['left']) : '' ?>">
            </div>
        </div>
    <?php }

    /**
     * @param $params array
     * @return void
     */
    protected static function renderColorControl(array $params){
        $field_name = $params['name'];
        $is_inner = isset($params['is_inner']) && $params['is_inner'] == true;
        ?>
        <div class="<?php echo !$is_inner ? 'mwpl-style-control' : '' ?> color-control-item mp-control-item" data-type="color" data-selectors-json="<?php echo isset($params['selectors']) ? esc_attr(json_encode($params['selectors'])) : '' ?>">
            <?php if (isset($params['label']) && !empty($params['label'])): ?>
                <label for="<?php echo esc_attr($field_name) ?>"><?php echo $params['label'] ?></label>
            <?php endif; ?>
            <input type="text" class="mp_color_picker" name="<?php echo esc_attr($field_name) ?>" value="<?php echo isset($params['value']) ? esc_attr($params['value']) : '' ?>">
        </div>
    <?php }

    /**
     * @param $params
     * @return void
     */
    protected static function renderBackgroundGroupControl(array $params){
        $field_name = $params['name'];
        $values = isset($params['values']) && is_array($params['values']) ? $params['values'] : null;
        $is_inner = isset($params['is_inner']) && $params['is_inner'] == true;
        $supports = isset($params['supports']) && is_array($params['supports']) ? $params['supports'] : null;
        $support_video = $supports && in_array('video', $supports);
        ?>
        <div class="<?php echo !$is_inner ? 'mwpl-style-control' : '' ?> mwpl-background-control-item mp-control-item" data-type="background" data-selectors-json="<?php echo isset($params['selectors']) ? esc_attr(json_encode($params['selectors'])) : '' ?>">
            <?php if (isset($params['label']) && !empty($params['label'])): ?>
                <label for="<?php echo esc_attr($field_name) ?>"><?php echo $params['label'] ?></label>
            <?php endif; ?>
            <div class="mwpl-inner-control option-select">
                <?php $type_value = $values && isset($values['type']) ? esc_attr($values['type']) : ''; ?>
                <select name="<?php echo esc_attr($field_name) ?>_type" class="background-type w100">
                    <option <?php selected($type_value, 'simple') ?> value="simple"><?php _e('Simple', 'mihanpanel') ?></option>
                    <option <?php selected($type_value, 'gradient') ?> value="gradient"><?php _e('Gradient', 'mihanpanel') ?></option>
                    <option <?php selected($type_value, 'image') ?> value="image"><?php _e('Image', 'mihanpanel') ?></option>
                    <?php if ($support_video): ?>
                        <option <?php selected($type_value, 'video') ?> value="video"><?php _e('Video', 'mihanpanel') ?></option>
                    <?php endif; ?>
                </select>
                <div class="mwpl-bg-control-option all">
                    <?php
                    self::renderColorControl([
                        'label' => __('Background Color', 'mihanpanel'),
                        'name' =>  esc_attr($field_name) . '_color',
                        'value' => $values && isset($values['color']) ? $values['color'] : '',
                        'is_inner' => true
                    ]);
                    ?>
                </div>
                <div class="mwpl-bg-control-option gradient-options" style="display:<?php echo $type_value === 'gradient' ? 'block' : 'none' ?>;">
                    <?php
                    self::renderColorControl([
                        'label' => __('Background Secondary Color', 'mihanpanel'),
                        'name' =>  esc_attr($field_name) . '_color2',
                        'value' => $values && isset($values['color2']) ? $values['color2'] : '',
                        'is_inner' => true
                    ]);
                    self::renderRangeControl([
                        'label' => __('Gradient Direction', 'mihanpanel'),
                        'name' =>  esc_attr($field_name) . '_direction',
                        'value' => $values && isset($values['direction']) ? $values['direction'] : '',
                        'current_unit' => 'deg',
                        'min' => 0,
                        'max' => 360,
                        'units' => ['deg'],
                        'is_inner' => true
                    ]);
                    ?>
                </div>
                <?php if ($support_video): ?>
                <div class="mwpl-bg-control-option video-options" style="display:<?php echo $type_value === 'video' ? 'block' : 'none' ?>;">
                    <div class="uploader-section">
                        <label><?php _e('Video', 'mihanpanel') ?></label>
                        <div class="mwpl-image-selector">
                            <input type="text" name="<?php echo esc_attr($field_name) . '_video' ?>" dir="ltr" value="<?php echo $values && isset($values['video']) ? esc_attr($values['video']) : '' ?>" data-setting="background_video">
                            <button type="button" class="mwpl-upload-button" data-type="video" data-value-type="url"><?php echo __('Select', 'mihanpanel') ?></button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="mwpl-bg-control-option image-options" style="display:<?php echo $type_value === 'image' ? 'block' : 'none' ?>;">
                    <div class="uploader-section">
                        <label><?php _e('Image', 'mihanpanel') ?></label>
                        <div class="mwpl-image-selector">
                            <input type="text" name="<?php echo esc_attr($field_name) . '_image' ?>" dir="ltr" value="<?php echo $values && isset($values['image']) ? esc_attr($values['image']) : '' ?>" data-setting="background_image">
                            <button type="button" class="mwpl-upload-button" data-type="image" data-value-type="url"><?php echo __('Select', 'mihanpanel') ?></button>
                        </div>
                    </div>
                    <div class="option-repeat option-select">
                        <label for="<?php echo esc_attr($field_name) . '_repeat' ?>"><?php echo __('Background Repeat', 'mihanpanel') ?></label>
                        <?php $repeat_value = $values && isset($values['repeat']) ? esc_attr($values['repeat']) : ''; ?>
                        <select name="<?php echo esc_attr($field_name) . '_repeat' ?>" data-setting="background_repeat">
                            <option <?php selected($repeat_value, 'no-repeat') ?> value="no-repeat"><?php echo __('No Repeat', 'mihanpanel') ?></option>
                            <option <?php selected($repeat_value, 'repeat') ?> value="repeat"><?php echo __('Repeat', 'mihanpanel') ?></option>
                            <option <?php selected($repeat_value, 'repeat-x') ?> value="repeat-x"><?php echo __('X Repeat', 'mihanpanel') ?></option>
                            <option <?php selected($repeat_value, 'repeat-y') ?> value="repeat-y"><?php echo __('Y Repeat', 'mihanpanel') ?></option>
                        </select>
                    </div>
                    <div class="option-size option-select">
                        <label for="<?php echo esc_attr($field_name) . '_size' ?>"><?php echo __('Background Size', 'mihanpanel') ?></label>
                        <?php $size_value = $values && isset($values['size']) ? esc_attr($values['size']) : ''; ?>
                        <select name="<?php echo esc_attr($field_name) . '_size' ?>" data-setting="background_size">
                            <option <?php selected($size_value, 'auto') ?> value="auto"><?php echo __('Auto', 'mihanpanel') ?></option>
                            <option <?php selected($size_value, 'contain') ?> value="contain"><?php echo __('Contain', 'mihanpanel') ?></option>
                            <option <?php selected($size_value, 'cover') ?> value="cover"><?php echo __('Cover', 'mihanpanel') ?></option>
                        </select>
                    </div>
                    <div class="option-position option-select">
                        <label for="<?php echo esc_attr($field_name) . '_position' ?>"><?php echo __('Background Position', 'mihanpanel') ?></label>
                        <?php $position_value = $values && isset($values['position']) ? esc_attr($values['position']) : ''; ?>
                        <select name="<?php echo esc_attr($field_name) . '_position' ?>" data-setting="background_position">
                            <option value=""><?php _e('Default', 'mihanpanel') ?></option>
                            <option <?php selected($position_value, 'center center') ?> value="center center"><?php _e('Center Center', 'mihanpanel') ?></option>
                            <option <?php selected($position_value, 'center left') ?> value="center left"><?php _e('Center Left', 'mihanpanel') ?></option>
                            <option <?php selected($position_value, 'center right') ?> value="center right"><?php _e('Center Right', 'mihanpanel') ?></option>
                            <option <?php selected($position_value, 'top center') ?> value="top center"><?php _e('Top Center', 'mihanpanel') ?></option>
                            <option <?php selected($position_value, 'top left') ?> value="top left"><?php _e('Top Left', 'mihanpanel') ?></option>
                            <option <?php selected($position_value, 'top right') ?> value="top right"><?php _e('Top Right', 'mihanpanel') ?></option>
                            <option <?php selected($position_value, 'bottom center') ?> value="bottom center"><?php _e('Bottom Center', 'mihanpanel') ?></option>
                            <option <?php selected($position_value, 'bottom left') ?> value="bottom left"><?php _e('Bottom Left', 'mihanpanel') ?></option>
                            <option <?php selected($position_value, 'bottom right') ?> value="bottom right"><?php _e('Bottom Right', 'mihanpanel') ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    <?php }

    protected static function renderCheckbox($params){
        $field_name = $params['name'];
        $field_id = isset($params['id']) && !empty($params['id']) ? $params['id'] : $field_name;
        $is_checked = isset($params['is_checked']) && $params['is_checked'] === true;
        $is_inner = isset($params['is_inner']) && $params['is_inner'] == true;
        $input_atts = isset($params['input_atts']) && is_array($params['input_atts']) ? $params['input_atts'] : null;
        $disabled = isset($params['disabled']) && $params['disabled'] === true;
        ?>
        <div class="<?php echo !$is_inner ? 'mwpl-content-control' : '' ?> checkbox-item mp-control-item" data-type="checkbox">
            <div class="mwpl-field-wrap mwpl-checkbox-wrap">
                <?php if (isset($params['label']) && !empty($params['label'])): ?>
                    <label for="<?php echo $field_name ?>"><?php echo $params['label'] ?></label>
                <?php endif; ?>
                <div class="mwpl-checkbox-wrap">
                    <input type="checkbox" name="<?php echo $field_name ?>" id="<?php echo $field_id ?>" <?php self::renderInputAtts($input_atts); ?> class="mwpl-checkbox" <?php echo !$disabled && $is_checked ? 'checked' : '' ?>  <?php echo $disabled ? 'disabled' : '' ?>>
                    <label for="<?php echo $field_id ?>" class="mwpl-checkbox-label">
                        <span class="tick-mark"></span>
                    </label>
                </div>
            </div>
            <?php if (isset($params['description']) && !empty($params['description'])): ?>
                <div class="mwpl-field-description"><?php echo $params['description'] ?></div>
            <?php endif; ?>
        </div>
    <?php }
}