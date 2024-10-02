<?php
use PISOL\EDD\BASE\OrderEstimateCalculator;
use PISOL\EDD\BASE\ShippingMethod;
use PISOL\EDD\BASE\Message;
use PISOL\EDD\BASE\Template;

class pisol_ppscw_shipping_methods_estimate_new{

    function __construct(){
        
        if(!pisol_ppscw_estimate_pro_present()) return;

        if(!class_exists('PISOL\EDD\BASE\OrderEstimateCalculator') || !class_exists('PISOL\EDD\BASE\ShippingMethod') || !class_exists('PISOL\EDD\BASE\Message') || !class_exists('PISOL\EDD\BASE\Template')) return ;

        add_filter('pisol_ppscw_shipping_method_name', array($this, 'addShippingEstimate'),10,4);
    }

    function addShippingEstimate($title, $method, $product_id, $variation_id){

        $show_estimate = apply_filters('pisol_ppscw_show_estimate_dates', get_option('pi_ppscw_show_estimate_date',1), $product_id, $variation_id);

        if(empty($show_estimate)) return $title;

        if(!empty($variation_id)){
            $product_id = $variation_id;
        }

        $method_name = $method->id;
        $shipping = ShippingMethod::get_shipping_method( $method_name );
        $estimate = $this->get_estimate($product_id, $shipping);
        $msg = Message::msg($estimate, 'shipping');
        $estimate_msg = Template::get_template_shipping($method_name, $msg);
        return $title.' '. $estimate_msg;
    }

    function get_estimate($product_id, $shipping){
        $estimate_based_on = get_option('pi_ppscw_show_estimate_as_per','cart'); // product or cart

        if($estimate_based_on == 'cart'){
            $estimate = OrderEstimateCalculator::get_order_estimate($shipping);
        }else{
            if (version_compare(PI_EDD_VERSION, '4.7.21.10', '>=')) {
                $estimate = OrderEstimateCalculator::get_order_estimate($shipping, [$product_id => 1]);
            }else{
                $estimate = OrderEstimateCalculator::get_order_estimate($shipping, [$product_id]);
            }
        }

        return $estimate;
    }

}

new pisol_ppscw_shipping_methods_estimate_new();