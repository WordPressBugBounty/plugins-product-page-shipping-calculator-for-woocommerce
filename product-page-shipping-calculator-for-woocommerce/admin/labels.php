<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class pisol_ppscw_labels_setting{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'labels-setting';

    private $tab_name = "Labels";

    private $setting_key = 'pisol_ppscw_labels_setting';

    public $tab;

    public $settings;


    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        $this->tab_name = __("Labels",'pisol-product-page-shipping-calculator-woocommerce');
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(

             array('field'=>'title', 'class'=> 'bg-dark2 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Product page calculator labels", 'pisol-product-page-shipping-calculator-woocommerce'), 'type'=>"setting_category"),

             array('field'=>'pi_ppscw_no_address_added_yet', 'label'=>__('When user hasn\'t entered an address','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'text', 'default'=>'Insert your location to get the shipping method', 'desc'=>"Insert your location to get the shipping method"),
            
            array('field'=>'pi_ppscw_open_drawer_button_text', 'label'=>__('Button – Calculate Shipping','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'text', 'default'=>'Select delivery location', 'desc'=>__('This message will be shown inside calculate shipping button that will open the form','pisol-product-page-shipping-calculator-woocommerce')),

            array('field'=>'pi_ppscw_update_button_text', 'label'=>__('Button – Update Address','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'text', 'default'=>'Update Address', 'desc'=>__('Address form submission button text','pisol-product-page-shipping-calculator-woocommerce')),


             array('field'=>'pi_ppscw_above_shipping_methods', 'label'=>__('Above the shipping method list','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'text', 'default'=>'Shipping methods available for your location:', 'desc'=>__('[country] => shortcode can be used to show the name of the country selected by the customer','pisol-product-page-shipping-calculator-woocommerce')),

             array('field'=>'pi_ppscw_no_shipping_methods_msg', 'label'=>__('When no shipping methods are available','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'text', 'default'=>'No shipping methods available for your location', 'desc'=>""),

             array('field'=>'pi_ppscw_select_variation_msg', 'label'=>__('When no product variation is selected','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'text', 'default'=>'Select variation', 'desc'=>__("This is used on variable product when no variation is selected", 'pisol-product-page-shipping-calculator-woocommerce')),

             array('field'=>'title', 'class'=> 'bg-dark2 text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Address form popup labels", 'pisol-product-page-shipping-calculator-woocommerce'), 'type'=>"setting_category"),

             array('field'=>'pi_ppscw_popup_title', 'type'=>'text', 'default'=>'Set your delivery location','label'=>__('Popup title','pisol-product-page-shipping-calculator-woocommerce'),'desc'=>""),

            array('field'=>'pi_ppscw_details_saved', 'label'=>__('Your details are saved','pisol-product-page-shipping-calculator-woocommerce'),'type'=>'text', 'default'=>__('Your details are saved','pisol-product-page-shipping-calculator-woocommerce'), 'desc'=>__("This is shown when the details are saved from the popup form", 'pisol-product-page-shipping-calculator-woocommerce')),

            array('field' => 'pi_ppscw_popup_update_address_title', 'type' => 'text', 'default' => 'Update Address','label' => __('Update address button','pisol-product-page-shipping-calculator-woocommerce'),'desc'=>""),
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
        ?>
        <a class=" pi-side-menu  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo esc_url(admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab )); ?>">
        <span class="dashicons dashicons-tag"></span> <?php echo esc_html($this->tab_name); ?> 
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
        <input type="submit" class="my-3 btn btn-primary btn-md" value="<?php esc_attr_e('Save Changes','pisol-product-page-shipping-calculator-woocommerce'); ?>" />
        </form>
       <?php
    }

}


