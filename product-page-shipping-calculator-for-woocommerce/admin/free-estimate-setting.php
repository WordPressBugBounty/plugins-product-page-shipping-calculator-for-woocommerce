<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class pisol_ppscw_free_estimate_setting{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'estiamte-setting';

    private $tab_name = "Show estimate delivery date";

    private $setting_key = 'pisol_ppscw_free_estimate_setting';

    public $tab;

    public $settings;


    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(

            array('field'=>'pi_ppscw_show_estimate_date1', 'label'=>__('Show estimate date for each shipping methods on product page','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'switch', 'default'=> 1, 'desc'=>__('This will show the estimate date below each of the shipping method','pisol-product-page-shipping-calculator-woocommerce'),'pro'=>true),
            
            array('field'=>'pi_ppscw_disable_view_shipping_method1', 'label'=>__('Don\'t show shipping methods','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'switch', 'default'=> 0, 'desc'=>__('It will only update  the location selected and will not show the shipping method for the selected location (If you are using our estimate plugin then it will update the estimate date as well)','pisol-product-page-shipping-calculator-woocommerce'),'pro'=>true),

            array('field'=>'pi_ppscw_show_estimate_as_per1', 'label'=>__('Shipping method estimate as per','pisol-product-page-shipping-calculator-woocommerce'), 'type'=>'select', 'default'=> 'product', 'value'=>array('product'=>__('Product estimate','pisol-product-page-shipping-calculator-woocommerce'), 'cart'=>__('Cart estimate','pisol-product-page-shipping-calculator-woocommerce')), 'desc'=>__('It will show the estimate date for each of the shipping method:<br> Product estimate date=> estimate will be based on this particular product (useful when you ship item separately)<br>Cart estimate date => estimate will be based on all the product in cart (useful when you ship item in order together)','pisol-product-page-shipping-calculator-woocommerce'),'pro'=>true),

        );

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),4);

        $this->register_settings();
        
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $this->tab_name = __("Show estimate delivery date",'pisol-product-page-shipping-calculator-woocommerce');
        ?>
        <a class=" pi-side-menu  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo esc_url(admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab )); ?>">
        <span class="dashicons dashicons-calendar-alt"></span> <?php echo esc_html($this->tab_name); ?> 
        </a>
        <?php
    }

    function tab_content(){
        
       ?>
       <div id="row_title" class="pisol-form-element-row row py-4 border-bottom align-items-center bg-dark opacity-75 text-light">
            <div class="col-12">
                <h2 class="mt-0 mb-0 text-light font-weight-light h4">
                    Estimate delivery date
                </h2>
            </div>
        </div>
       <div class="row">
            <div class="col-12 col-md-6">
                <?php do_action( 'pisol_ppscw_dependency_install'); ?>
            </div>
            <div class="col-12 col-md-6">
                <img class="img-fluid my-3" src="<?php echo esc_url(plugin_dir_url( __FILE__ )); ?>img/product.png">
            </div>
       </div>
        
       <?php
    }

}


