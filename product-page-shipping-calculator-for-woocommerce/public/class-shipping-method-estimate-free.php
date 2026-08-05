<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class pisol_ppscw_shipping_methods_estimate_free{

    function __construct(){
        
        if(!pisol_ppscw_estimate_free_present()) return;

        if(!class_exists('Pi_Edd_Shipping_Estimate_Calculator')) return ;

        add_filter('pisol_ppscw_shipping_method_name', array($this, 'addShippingEstimate'),10,4);
    }

    function addShippingEstimate($title, $method, $product_id, $variation_id){

        $show_estimate = apply_filters('pisol_ppscw_show_estimate_dates', get_option('pi_ppscw_show_estimate_date',1), $product_id, $variation_id);

        if(empty($show_estimate)) return $title;

        $estimate = $this->methodEstimate($method, $product_id, $variation_id);
        $msg = Pi_Edd_Shipping_Estimate_Calculator::get_message($estimate);

        if(empty($msg)) return self::remove_trailing_parentheses($title);

        return self::remove_trailing_parentheses($title).' <div class="pi-edd-method-estimate">'.$msg.'</div>';
    }

    /**
     * Removes a trailing "(...)" segment from a title string.
     * e.g. "Express shipping (Delivery by Aug 16, 2026)" => "Express shipping"
     * We only remove bracket when block support is enabled in the estimate plugin settings.
     *
     * @param string $title
     * @return string
     */
    static function remove_trailing_parentheses( $title ) {
        $useing_block = get_option('pi_edd_estimate_block_support', 1);

        if(empty($useing_block)) return $title;

        $title = (string) $title;
        // Normalize non-breaking spaces / HTML entities to regular spaces.
        $title = str_replace( '&nbsp;', ' ', $title );

        // Remove the last "(...)" occurrence (non-nested) in the string.
        $title = preg_replace( '/\([^)]*\)(?!.*\()/s', '', $title );

        // Collapse extra whitespace left behind and trim.
        $title = trim( preg_replace( '/\s+/', ' ', $title ) );

        return $title;
    }

    function methodEstimate($method, $product_id, $variation_id){
        $estimate_based_on = get_option('pi_ppscw_show_estimate_as_per','product'); // product or cart
        $shipping_method = $this->getShipping($method);
        if($estimate_based_on == 'product'){
            return $this->productMethodEstimate($shipping_method, $product_id, $variation_id);
        }else{
            return $this->cartMethodEstimate($shipping_method, $product_id, $variation_id);
        }
    }

    function productMethodEstimate($shipping_method, $product_id, $variation_id){
        $cart_items =  $this->onlyProductBasedCartItems($product_id, $variation_id);
        $estimate = Pi_Edd_Shipping_Estimate_Calculator::shippingEstimate($shipping_method, $cart_items);
        return $estimate;
    }

    function onlyProductBasedCartItems($product_id, $variation_id){
        $cart_items = WC()->cart->get_cart_contents();
        if(!is_array($cart_items)) return $cart_items;

        foreach($cart_items as $key => $item){
            $cart_product_id = $item['product_id'];
            $cart_variation_id = $item['variation_id'];
            if($product_id != $cart_product_id || $cart_variation_id != $variation_id){
                unset($cart_items[$key]);
            }
        }
        return $cart_items;
    }

    
    function cartMethodEstimate($shipping_method, $product_id, $variation_id){
        $cart_items =  WC()->cart->get_cart_contents();
        $estimate = Pi_Edd_Shipping_Estimate_Calculator::shippingEstimate($shipping_method, $cart_items);
        return $estimate;
    }

    function getShipping($method){
        $method_name = $method->id;
        return $method_name;
    }

}

new pisol_ppscw_shipping_methods_estimate_free();