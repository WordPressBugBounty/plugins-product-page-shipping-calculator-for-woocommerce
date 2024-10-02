<?php
class Pisol_Product_Page_Shipping_Calculator_Woocommerce_Public {

	
	private $plugin_name;

	
	private $version;

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		

	}

	

	
	public function enqueue_styles() {

		

	}

	
	public function enqueue_scripts() {

		if(function_exists('is_product') && is_product()){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pisol-product-page-shipping-calculator-woocommerce-public.js', array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n' ), $this->version, false );

			wp_localize_script($this->plugin_name, 'pi_ppscw_data', array(
				'select_variation' => get_option('pi_ppscw_select_variation_msg','Select variation'),
				'disable_shipping_method_list' => self::show_shipping_method(),
				'auto_select_country' => apply_filters('pisol_ppscw_auto_select_country', self::singleShippingCountry()),
				'insert_location_message' => get_option('pi_ppscw_no_address_added_yet','Insert your location to get the shipping method'),
				'auto_load_enabled' => get_option('pi_ppscw_auto_calculation', 'enabled'),
			));
		}

		$enable_popup = get_option('pi_ppscw_enable_badge',0);
		/**
		 * We do not want to load this JS and CSS when popup option is disabled
		 */
		if(!empty($enable_popup)){
			wp_enqueue_script( $this->plugin_name.'-popup', plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name.'-address-form', plugin_dir_url( __FILE__ ) . 'js/address-form.js', array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n' ), $this->version, false );
			wp_enqueue_style( $this->plugin_name.'-popup', plugin_dir_url( __FILE__ ) . 'css/magnific-popup.css' );
		}

		wp_localize_script( 'jquery', 'pi_ppscw_setting', array(
			'wc_ajax_url' => WC_AJAX::get_endpoint( '%%endpoint%%' ), 'ajaxUrl'=> admin_url('admin-ajax.php'), 
			'loading' => 'Loading..', 
			'auto_select_country'=> apply_filters('pisol_ppscw_auto_select_country', self::singleShippingCountry()),
			'load_location_by_ajax' => get_option('pi_ppscw_load_location_by_ajax', 0)
			) 
		);

		wp_enqueue_style( $this->plugin_name.'-address-form', plugin_dir_url( __FILE__ ) . 'css/address-form.css' );

	}

	static function singleShippingCountry(){
		if(!function_exists('WC') || !is_object(WC()->countries)) return false;

		$countries = WC()->countries->get_shipping_countries();

		if(count($countries) == 1) {
			foreach ( WC()->countries->get_shipping_countries() as $key => $value ) {
				return $key;
			}
		}

		return false;
	}

	static function show_shipping_method(){
		if(!pisol_ppscw_estimate_pro_present()) return 0;

		return get_option('pi_ppscw_disable_view_shipping_method','0');
	}

}
