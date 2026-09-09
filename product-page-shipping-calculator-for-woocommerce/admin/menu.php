<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class pisol_ppscw_menu{

    public $plugin_name;
    public $menu;
    public $version;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));
    }

    function plugin_menu(){
        
        $this->menu = add_submenu_page(
            'woocommerce',
            __( 'Shipping Calculator', 'isol-product-page-shipping-calculator-woocommerce'),
            __( 'Shipping Calculator', 'isol-product-page-shipping-calculator-woocommerce'),
            'manage_options',
            'pisol-shipping-calculator-setting',
            array($this, 'menu_option_page')
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
    }

    public function bootstrap_style() {
        //wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pisol-product-page-shipping-calculator-woocommerce-admin.css', array(), $this->version, 'all' );

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pisol-product-page-shipping-calculator-woocommerce-admin.js', array('jquery'), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name."_admin", plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );

        wp_enqueue_script( $this->plugin_name."_quick_save", plugin_dir_url( __FILE__ ) . 'js/pisol-quick-save.js', array('jquery'), $this->version, 'all' );

        if(function_exists('WC') && is_object(WC()->countries)){
            $countries = WC()->countries->get_shipping_countries();

            if(count($countries) == 1) return;

            $js = 'jQuery(function($){
                jQuery("#row_pi_ppscw_remove_country .custom-control, #row_pi_ppscw_remove_country_add_form .custom-control").html($("#hidden-msg"));
            });';
            wp_add_inline_script('jquery', $js, 'after');
        }
    }

    function menu_option_page(){
        if(function_exists('settings_errors')){
            settings_errors();
        }
        ?>
        <div class="pisol-container">
            <div class="pisol-header">
                <div id="pisol-header-bar">
                    <a href="https://www.piwebsolution.com/" target="_blank"><img id="pi-logo" class="pisol-img-fluid" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>img/pi-web-solution.svg"></a>
                </div>
            </div>

            <div class="pisol-left-sidebar">
                <div id="pisol-side-menu" class="mb-4 rounded py-3 ">
                    <?php do_action($this->plugin_name.'_tab'); ?>
                </div>
                <?php do_action($this->plugin_name.'_promotion'); ?>
            </div>

            <div class="pisol-content">
                <?php do_action($this->plugin_name.'_tab_content'); ?>
            </div>
        </div>
        <?php
    }

    function promotion(){
        
    }

}