<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Ppscw_Dependency{
    
    static $instance = null;

    public $dependency = [
        'estimate-delivery-date-for-woocommerce/pi-edd.php',
        'estimate-delivery-date-for-woocommerce-pro/pi-edd.php',
    ];

    public $plugin = 'estimate-delivery-date-for-woocommerce';
    public $plugin_file = 'estimate-delivery-date-for-woocommerce/pi-edd.php';
    public $pro_plugin_file = 'estimate-delivery-date-for-woocommerce-pro/pi-edd.php';

    public $plugin_page = 'estimate-delivery-date-for-woocommerce';

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action( 'pisol_ppscw_dependency_install', array( $this, 'notice' ) );
        add_action( 'wp_ajax_install_dependency_plugin_' . $this->plugin_page, array( $this, 'install_plugin' ) );
    }

    public function notice() {
        if( $this->dependency_check() ){
            return ;
        }
        $install_url = wp_nonce_url(
            admin_url(
                'update.php?action=install-plugin&plugin=' . $this->plugin
            ),
            'install-plugin_' . $this->plugin
        );
        ?>
        <div class="notice notice-error mt-3" style="display:flex; align-items: center; justify-content: space-between;margin-bottom:20px;">
            <p class="my-0">
            <strong><?php esc_html_e( 'Estimate Delivery Date for WooCommerce module is required.', 'pisol-product-page-shipping-calculator-woocommerce' ); ?></strong><br>
            <?php esc_html_e( 'To show the estimated delivery date for shipping method, this site needs the Estimate Delivery Date for WooCommerce module installed and active.', 'pisol-product-page-shipping-calculator-woocommerce' ); ?>
            <br><br>
            <a href="<?php echo esc_url( $install_url ); ?>" id="install-dependency-plugin-<?php echo esc_html($this->plugin_page); ?>" class="button button-primary" style="margin-top:0 !important;"><?php esc_html_e( 'Install Estimate Delivery Date for WooCommerce', 'pisol-product-page-shipping-calculator-woocommerce' ); ?></a>
            </p>
        </div>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                var button = document.getElementById('install-dependency-plugin-<?php echo esc_html($this->plugin_page); ?>');
                if (!button) {
                    return;
                }

                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    var button = this;
                    button.disabled = true;
                    button.innerText = 'Installing...';

                    jQuery.post(ajaxurl, {
                        action: 'install_dependency_plugin_<?php echo esc_html($this->plugin_page); ?>',
                        nonce: '<?php echo esc_js( wp_create_nonce( 'install_dependency_nonce_' . $this->plugin_page ) ); ?>',
                    }, function(response) {
                        location.reload();
                    });
                });
            });
        </script>
        <?php
    }

    function dependency_check() {
        if(defined('PI_EDD_VERSION')){
            return true;
        }

        return false;
    }

    function install_plugin() {
        if ( ! current_user_can( 'install_plugins' ) ) {
            wp_send_json_error( 'Unauthorized', 403 );
        }

        $nonce = filter_input( INPUT_POST, 'nonce' );
        if ( ! wp_verify_nonce( $nonce, 'install_dependency_nonce_' . $this->plugin_page ) ) {
            wp_send_json_error( 'Invalid nonce', 400 );
        }

        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

        if (file_exists(WP_PLUGIN_DIR . '/' . $this->pro_plugin_file)){
            $activate_result = activate_plugin( $this->pro_plugin_file );

            if ( is_wp_error( $activate_result ) ) {
                wp_send_json_error( [ 'message' => 'Plugin activation failed.' ] );
            }

            update_option( 'pisol_estimate-delivery-date-for-woocommerce', 'disable' );
            update_option('pi_edd_show_estimate_on_each_method', 1);
            delete_option( 'pi_cefw_do_activation_redirect' );
            wp_send_json_success(['message' => 'Module already installed.']);
        }

        if (file_exists(WP_PLUGIN_DIR . '/' . $this->plugin_file)){
            $activate_result = activate_plugin( $this->plugin_file );

            if ( is_wp_error( $activate_result ) ) {
                wp_send_json_error( [ 'message' => 'Plugin activation failed.' ] );
            }

            update_option( 'pisol_estimate-delivery-date-for-woocommerce', 'disable' );
            update_option('pi_edd_show_estimate_on_each_method', 1);
            delete_option( 'pi_cefw_do_activation_redirect' );
            wp_send_json_success(['message' => 'Module already installed.']);
        }

        $api = plugins_api('plugin_information', ['slug' => $this->plugin]);
        if (is_wp_error($api)) {
            wp_send_json_error(['message' => 'Failed to fetch plugin information.']);
        }

        $upgrader = new \Plugin_Upgrader(new \Automatic_Upgrader_Skin());
        $result = $upgrader->install($api->download_link);

        if (is_wp_error($result) || !$result) {
            wp_send_json_error(['message' => 'Plugin installation failed.']);
        }

        $activate_result = activate_plugin( $this->plugin_file );
        if ( is_wp_error( $activate_result ) ) {
            wp_send_json_error( [ 'message' => 'Plugin installed but activation failed.' ] );
        }

        update_option('pisol_estimate-delivery-date-for-woocommerce', 'disable');
        update_option('pi_edd_show_estimate_on_each_method', 1);
        delete_option( 'pi_cefw_do_activation_redirect' );

        wp_send_json_success(['message' => 'Module installed and activated successfully.']);
    }
}

Ppscw_Dependency::get_instance();