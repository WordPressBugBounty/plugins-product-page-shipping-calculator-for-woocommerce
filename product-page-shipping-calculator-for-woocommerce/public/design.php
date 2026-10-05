<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class pisol_ppscw_design{

    public $plugin_name;
    public $version;
    
    function __construct($plugin_name, $version){
        $this->plugin_name = $plugin_name;
		$this->version = $version;
        add_action('wp_enqueue_scripts', array($this, 'styles'));
    }

    function styles(){
        if(function_exists('is_product') && is_product()){
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 	'css/pisol-product-page-shipping-calculator-woocommerce-public.css', array(), $this->version, 'all' );
            $this->addInlineStyle();
		}
    }

    function addInlineStyle(){
        $msg_bg_color = self::gradient_helper(get_option('pi_ppscw_msg_background_color',array('color1' => "#FFF4EF", 'color2' => "#FFE0D3", 'direction' => 135)));
        $msg_general_text_color = get_option('pi_ppscw_msg_font_color','#000000');
        $msg_method_text_color = get_option('pi_ppscw_msg_font_color_shipping_method','#000000');
        $msg_method_cost_text_color = get_option('pi_ppscw_msg_font_color_shipping_cost','#000000');

        $button_bg_color = self::gradient_helper(get_option('pi_ppscw_calculate_shipping_bg_color',array('color1' => "#EE6A47", 'color2' => "#D9472B", 'direction' => 135)));
        
        $button_text_color = empty(get_option('pi_ppscw_calculate_shipping_text_color','')) ? '#ffffff' : get_option('pi_ppscw_calculate_shipping_text_color','');

        $update_add_button_bg_color = self::gradient_helper(get_option('pi_ppscw_update_address_bg_color',array('color1' => "#EE6A47", 'color2' => "#D9472B", 'direction' => 135)));
        $update_add_button_text_color = empty(get_option('pi_ppscw_update_address_text_color','')) ? '#ffffff' : get_option('pi_ppscw_update_address_text_color','');

        $css = "
            /* CSS Custom Properties for Design Customization */
            .pisol-ppscw-container {
                --pisol-msg-bg-color: {$msg_bg_color};
                --pisol-msg-text-color: {$msg_general_text_color};
                --pisol-method-text-color: {$msg_method_text_color};
                --pisol-method-cost-color: {$msg_method_cost_text_color};
                --pisol-btn-calc-bg: {$button_bg_color};
                --pisol-btn-calc-color: {$button_text_color};
                --pisol-btn-update-bg: {$update_add_button_bg_color};
                --pisol-btn-update-color: {$update_add_button_text_color};
            }
        ";
        wp_add_inline_style($this->plugin_name, $css);
    }

    static function gradient_helper($saved_value){
        $v = self::normalize_gradient($saved_value);
        return "linear-gradient({$v['direction']}deg, {$v['color1']}, {$v['color2']})";
    }

    static function normalize_gradient($value, $default = null){
        $fallback = '#ee6443';

        if(is_string($value) && $value !== ''){
            $c = sanitize_hex_color($value) ?: $fallback;
            return ['color1' => $c, 'color2' => $c, 'direction' => 90];
        }

        if(!is_array($value)){
            // empty / missing: use the field's default if it has one
            if($default !== null && $default !== $value){
                return self::normalize_gradient($default);
            }
            return ['color1' => $fallback, 'color2' => $fallback, 'direction' => 90];
        }

        return [
            'color1'    => sanitize_hex_color($value['color1'] ?? '') ?: $fallback,
            'color2'    => sanitize_hex_color($value['color2'] ?? '') ?: $fallback,
            'direction' => in_array((int)($value['direction'] ?? 90), [0,45,90,135,180,225,270,315], true)
                            ? (int)$value['direction'] : 90,
        ];
    }

    static function get_box_shadow($option_name, $default = 'none'){
        $value = get_option($option_name, $default);

        if(!is_string($value)){
            return '';
        }

        $value = strtolower(trim($value));

        switch($value){
            case 'soft':
                return 'box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.08);';
            case 'medium':
            case 'medim':
                return 'box-shadow: 0 4px 6px rgba(0,0,0,0.15), 0 2px 4px rgba(0,0,0,0.12);';
            case 'strong':
                return 'box-shadow: 0 10px 20px rgba(0,0,0,0.25), 0 6px 6px rgba(0,0,0,0.20);';
            case 'glow':
                return 'box-shadow: 0 0 15px rgba(255,106,136,0.6), 0 0 30px rgba(255,154,139,0.4);';
            case 'none':
            default:
                return '';
        }
    }
}

new pisol_ppscw_design($this->plugin_name, $this->version);