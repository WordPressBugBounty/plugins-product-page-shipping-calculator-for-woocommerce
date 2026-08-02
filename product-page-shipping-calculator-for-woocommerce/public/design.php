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
        $msg_bg_color = get_option('pi_ppscw_msg_background_color','#cccccc');
        $msg_general_text_color = get_option('pi_ppscw_msg_font_color','#000000');
        $msg_method_text_color = get_option('pi_ppscw_msg_font_color_shipping_method','#000000');
        $msg_method_cost_text_color = get_option('pi_ppscw_msg_font_color_shipping_cost','#000000');
        $button_bg_color = empty(get_option('pi_ppscw_calculate_shipping_bg_color',''))? '#ee6443' : get_option('pi_ppscw_calculate_shipping_bg_color','');
        $button_text_color = empty(get_option('pi_ppscw_calculate_shipping_text_color','')) ? '#ffffff' : get_option('pi_ppscw_calculate_shipping_text_color','');

        $update_add_button_bg_color = empty(get_option('pi_ppscw_update_address_bg_color',''))? '#ee6443' : get_option('pi_ppscw_update_address_bg_color','');
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
}

new pisol_ppscw_design($this->plugin_name, $this->version);