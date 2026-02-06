<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ppscw_CustomFields{

    static $instance = null;

    public $allowed_tags;

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    } 

    function __construct()
    {
        $this->allowed_tags =  wp_kses_allowed_html('post');

        $this->allowed_tags['input'] = array(
            'type'        => true,
            'name'        => true,
            'value'       => true,
            'class'       => true,
            'id'          => true,
            'placeholder' => true,
            'checked'     => true,
            'readonly'    => true,
            'disabled'    => true,
            'size'        => true,
            'maxlength'   => true,
            'min'         => true,
            'max'         => true,
            'step'        => true,
            'pattern'     => true,
            'required'    => true,
            'autocomplete'=> true,
            'autofocus'   => true,
        );

        add_action('pisol_custom_field_ppscw_radio_select', array($this,'ppscw_radio_select'), 10, 2);
    }

    
    /**
     * Custom field for border radius with separate values for each corner
     */
    function ppscw_radio_select($setting, $saved_value) {
    
    $label = '<label class="h6 mb-0">'.wp_kses_post($setting['label']).'</label>';
    $desc = (isset($setting['desc'])) ? '<br><small>'.wp_kses($setting['desc'], $this->allowed_tags).'</small>' : "";

    $field = '';
    foreach ($setting['value'] as $key => $val) {
        $field .= '<div class="form-check my-3">';
        $field .= '<input class="form-check-input" type="radio" name="'.esc_attr($setting['field']).'" id="'.esc_attr($setting['field'].'_'.$key).'" value="'.esc_attr($key).'" '.checked($saved_value, $key, false).'>';
        $field .= '<label class="form-check-label font-italic ml-1" for="'.esc_attr($setting['field'].'_'.$key).'">'.esc_html($val).'</label>';
        $field .= '</div>';
    }

    $links = '';

    $this->bootstrap($setting, $label, $field, $desc, $links, 7);
}


    function bootstrap($setting, $label, $field, $desc = "", $links = "", $title_col = 5){
        $title_col = 6;
        $setting_col = 12 - $title_col;
        if($setting['type'] != 'hidden'){
        ?>
        <div id="row_<?php echo esc_attr($setting['field']); ?>"  class="pisol-form-element-row row py-4 border-bottom align-items-center <?php echo !empty($setting['class']) ? esc_attr($setting['class']) : ''; ?>">
            <div class="col-12 col-md-<?php echo esc_attr($title_col); ?>">
            <?php echo wp_kses($label, $this->allowed_tags); ?>
            <?php echo wp_kses($desc != "" ? $desc: "", $this->allowed_tags); ?>
            <?php echo wp_kses($links != "" ? $links: "", $this->allowed_tags); ?>
            </div>
            <div class="col-12 col-md-<?php echo esc_attr($setting_col); ?>">
            <?php echo $field; ?>
            </div>
        </div>
        <?php
        }else{
            ?>
            <div id="row_<?php echo esc_attr($setting['field']); ?>" class="row align-items-center">
            <div class="col-12 col-md-12">
            <?php echo wp_kses($field, $this->allowed_tags); ?>
            </div>
            </div>
            <?php
        }
    }

}

Ppscw_CustomFields::get_instance();