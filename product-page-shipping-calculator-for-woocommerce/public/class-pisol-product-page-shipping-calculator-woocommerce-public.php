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
			$this->addInlineStyle();
		}

		wp_localize_script( 'jquery', 'pi_ppscw_setting', array(
			'wc_ajax_url' => WC_AJAX::get_endpoint( '%%endpoint%%' ), 'ajaxUrl'=> admin_url('admin-ajax.php'), 
			'loading' => 'Loading..', 
			'auto_select_country'=> apply_filters('pisol_ppscw_auto_select_country', self::singleShippingCountry()),
			'load_location_by_ajax' => get_option('pi_ppscw_load_location_by_ajax', 0),
			'address_form_css_url' => plugin_dir_url( __FILE__ ) . 'css/address-form.css' // Add this line
			)
		);

	}

	function addInlineStyle(){
        $update_add_button_bg_color = get_option('pi_ppscw_popup_header_bg_color', '#e74c3c');
        $update_add_button_text_color = get_option('pi_ppscw_popup_header_text_color', '#FFFFFF');

        $css = "
			.pisol-ppscw-badge-icon {
				max-height: 20px;
				width: auto;
				display: inline-block;
				margin-right: 15px;
			}

			#pisol-ppscw-badge {
				display: flex;
				align-items: center;
				padding: 10px;
				background: #000;
				color: #fff;
				text-decoration: none;
			}

			#pisol-ppscw-badge-container {
				position: fixed;
				z-index: 999999;
			}

			#pisol-ppscw-badge-container.pisol-badge-bottom-right {
				bottom: 0;
				right: 20px;
			}

			#pisol-ppscw-badge-container.pisol-badge-bottom-left {
				bottom: 0;
				left: 20px;
			}

			#pisol-ppscw-badge-container.pisol-badge-top-right {
				top: 0;
				right: 20px;
			}

			#pisol-ppscw-badge-container.pisol-badge-top-left {
				top: 0;
				left: 20px;
			}

			#pisol-ppscw-badge-container.pisol-badge-right-center {
				top: 50%;
				right: 0;
			}

			#pisol-ppscw-badge-container.pisol-badge-left-center {
				top: 50%;
				left: 0;
			}

			#pisol-ppscw-badge-container.pisol-badge-right-center,
			#pisol-ppscw-badge-container.pisol-badge-left-center {
				top: 50%;
				transform: translateY(-50%);
			}

			#pisol-ppscw-badge-container.pisol-badge-right-center {
				right: 0;
			}

			#pisol-ppscw-badge-container.pisol-badge-left-center {
				left: 0;
			}

			#pisol-ppscw-badge-container.pisol-badge-right-center #pisol-ppscw-badge,
			#pisol-ppscw-badge-container.pisol-badge-left-center #pisol-ppscw-badge {
				writing-mode: vertical-rl;
				text-orientation: mixed;
			}

			#pisol-ppscw-badge-container.pisol-badge-left-center #pisol-ppscw-badge,
			#pisol-ppscw-badge-container.pisol-badge-right-center #pisol-ppscw-badge {
				transform: rotate(180deg); /* so text reads bottom-to-top on the left side, matching your current right-side direction */
			}

			#pisol-ppscw-badge-container.pisol-badge-left-center .pisol-ppscw-badge-icon,
			#pisol-ppscw-badge-container.pisol-badge-right-center .pisol-ppscw-badge-icon{
				margin-bottom:15px;
				margin-top:15px;
				margin-right:0;
				transform: rotate(90deg);
			}

            .pisol-ppscw-form-container{
                --pisol-form-update-address-button-bg-color: {$update_add_button_bg_color};
                --pisol-form-update-address-button-text-color: {$update_add_button_text_color};
            }
        ";
        wp_add_inline_style($this->plugin_name.'-popup', $css);
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
