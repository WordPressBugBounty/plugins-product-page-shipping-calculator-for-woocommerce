<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class pisol_ppscw_basic_option{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'default';

    private $tab_name = "Basic setting";

    private $setting_key = 'pisol_ppscw_basic_setting';

    public $tab;

    public $settings;


    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->tab_name = __("Basic setting",'pisol-product-page-shipping-calculator-woocommerce');

        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(
            array('field'=>'title', 'class'=> 'bg-dark2 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Position", 'pisol-product-page-shipping-calculator-woocommerce'), 'type'=>"setting_category", 'desc'=> __("Position of the shipping calculator on the product page", 'pisol-product-page-shipping-calculator-woocommerce')),

            array('field'=>'pi_ppscw_calc_position', 'label'=>__('Position of the calculator on product page','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'radio','value'=> array('woocommerce_after_add_to_cart_form' => __('After add to cart button', 'pisol-product-page-shipping-calculator-woocommerce'), 'woocommerce_before_add_to_cart_form' => __('Before add to cart button', 'pisol-product-page-shipping-calculator-woocommerce'), 'shortcode'=> __('Use shortcode [pi_shipping_calculator] to insert manually', 'pisol-product-page-shipping-calculator-woocommerce')), 'default'=>'woocommerce_after_add_to_cart_form', 'desc'=>__("Choose where the calculator appears on the product page.", 'pisol-product-page-shipping-calculator-woocommerce')),

            array('field'=>'pi_ppscw_result_position', 'label'=>__('Position of the calculator result','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'radio','value'=> array(
                'pi_ppscw_before_calculate_button' => __('Before "Calculate Shipping" button', 'pisol-product-page-shipping-calculator-woocommerce'), 
                'pi_ppscw_after_calculate_button' => __('After "Calculate Shipping" button', 'pisol-product-page-shipping-calculator-woocommerce'),
                'pi_ppscw_before_calculate_form' => __('Before shipping form (hidden container)', 'pisol-product-page-shipping-calculator-woocommerce'), 
                'pi_ppscw_after_calculate_form'=>__('After shipping form (hidden container)', 'pisol-product-page-shipping-calculator-woocommerce')), 
                'default'=>'pi_ppscw_before_calculate_button', 'desc'=>__("Choose where the calculated shipping result will appear.", 'pisol-product-page-shipping-calculator-woocommerce')),

            array('field'=>'pi_ppscw_default_form_display', 'label'=>__('Shipping calculator form default state','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'radio','value'=> array('closed' => __('Closed by default', 'pisol-product-page-shipping-calculator-woocommerce'), 'open' => __('Open by default', 'pisol-product-page-shipping-calculator-woocommerce')), 'default'=>'closed', 'desc'=>__("Controls whether the calculator form is expanded or collapsed by default.", 'pisol-product-page-shipping-calculator-woocommerce')),

            array('field'=>'title', 'class'=> 'bg-dark2 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Auto Loading of Shipping Method", 'pisol-product-page-shipping-calculator-woocommerce'), 'type'=>"setting_category"),

            array('field'=>'pi_ppscw_auto_calculation', 'label'=>__('Auto calculation of shipping method','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'radio','value'=> array('enabled' => __('Enabled', 'pisol-product-page-shipping-calculator-woocommerce'), 'disabled' => __('Disabled', 'pisol-product-page-shipping-calculator-woocommerce')), 'default'=>'enabled', 'desc'=>__("When enabled, the shipping method will auto-update after entering an address. Otherwise, the user must click “Update Address” manually.", 'pisol-product-page-shipping-calculator-woocommerce')),

            array('field'=>'title', 'class'=> 'bg-dark2 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("If Product Page Has Caching", 'pisol-product-page-shipping-calculator-woocommerce'), 'type'=>"setting_category"),

             array('field'=>'pi_ppscw_load_location_by_ajax', 'label'=>__('Load user location data via AJAX','pisol-product-page-shipping-calculator-woocommerce'),'desc'=>__('Use this option if you have page caching enabled. It prevents caching of address data by loading it dynamically.','pisol-product-page-shipping-calculator-woocommerce'), 'type'=>'switch', 'default'=>0), 

            array('field'=>'title', 'class'=> 'bg-dark2 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Show Shipping Based on Quantity Field", 'pisol-product-page-shipping-calculator-woocommerce'), 'type'=>"setting_category"),

            array('field'=>'pi_ppscw_consider_quantity_field', 'label'=>__('Product Quantity Field','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'radio','value'=> array('consider-quantity' => __('Calculate for the quantity selected (more accurate)', 'pisol-product-page-shipping-calculator-woocommerce'), 'dont-consider-quantity-field' => __('Calculate for 1 unit until the product is in the cart', 'pisol-product-page-shipping-calculator-woocommerce')), 'default'=>'dont-consider-quantity-field', 'desc'=>__("Choose how many units the calculator should use when estimating shipping. Using the selected quantity gives a more accurate result because shipping rules often depend on quantity, weight, or total cost", 'pisol-product-page-shipping-calculator-woocommerce')),

            /** In future default should be  turned on */
            array('field'=>'pi_ppscw_ignore_cart_product', 'label' => __('Show shipping cost of the individual product only','pisol-product-page-shipping-calculator-woocommerce'), 'type' => 'switch', 'default' => 0, 'desc' => __('If enabled, the shipping cost will be shown for the product that user is checking for, ignoring the other product present in customer cart', 'pisol-product-page-shipping-calculator-woocommerce')),


            array('field'=>'title', 'class'=> 'bg-dark2 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Shipping price display", 'pisol-product-page-shipping-calculator-woocommerce'), 'type'=>"setting_category"),

             array('field'=>'pi_ppscw_free_shipping_price', 'label'=>__('Price next to free shipping methods','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'radio','value'=> array('nothing' => __('No price is shown', 'pisol-product-page-shipping-calculator-woocommerce'), 'zero' => __('Show "0"', 'pisol-product-page-shipping-calculator-woocommerce')), 'default'=>'nothing', 'desc'=>__("No price shown => It will not show any price next to Free shipping method,<br> Show Zero => $0 will be shown as charge next to free shipping method", 'pisol-product-page-shipping-calculator-woocommerce')),

             
             
        );

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        $this->register_settings();
        
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        ?>
        <a class=" pi-side-menu  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo esc_url(admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab )); ?>">
        <span class="dashicons dashicons-admin-home"></span> <?php echo esc_html($this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_ppscw($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="my-3 btn btn-md btn-primary" value="<?php esc_attr_e('Save Changes','pisol-product-page-shipping-calculator-woocommerce'); ?>" />
        </form>
        <div style="display:none;">
        <div id="hidden-shortcode-msg" class="alert alert-warning mt-2">
            Add shipping calculator manually on product page by shortcode <b>[pi_shipping_calculator]</b>
        </div>
        </div>
       <?php
    }

}


