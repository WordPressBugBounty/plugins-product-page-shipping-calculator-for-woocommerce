<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div style="padding: 20px; background: #fff; border: 1px solid #ddd; border-radius: 8px;" class="my-3">
  <h2>Take Full Control of Shipping with Conditional Rules 🚚</h2>
  <p>
    Want to customize which shipping options show up based on product, location, cart value, or user role?
  </p>
  <p>
    With the <strong>Conditional Shipping for WooCommerce</strong> plugin, you can easily create powerful rules to show or hide shipping methods. Perfect for stores with complex logistics or unique delivery requirements.
  </p>
  <ul>
    <li>✅ Hide shipping methods by product, category, or tag</li>
    <li>✅ Set conditions based on cart subtotal, weight, or quantity</li>
    <li>✅ Target users by role, country, state, or postcode</li>
    <li>✅ Combine multiple conditions for advanced control</li>
  </ul>

  <p style="font-weight: bold; color: #008000;">⚡ Eliminate confusion and show only the right shipping methods every time!</p>

  <p>
    <?php
        if ( $status === 'not_installed' ) {
            $install_url = wp_nonce_url(
                self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ),
                'install-plugin_' . $plugin_slug
            );
            echo '<p><a href="' . esc_url( $install_url ) . '" style="display: inline-block; background-color: #0073aa; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Install Now</a></p>';
        }elseif ( $status === 'inactive') {
            
                $activate_url = wp_nonce_url(
                    self_admin_url( 'plugins.php?action=activate&plugin=' . $plugin_file ),
                    'activate-plugin_' . $plugin_file
                );
                echo '<p><a href="' . esc_url( $activate_url ) . '" style="display: inline-block; background-color: #0073aa; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Activate</a></p>';
             
        }else{
                echo '<p><em>The plugin is already active.</em></p>';
        }
    ?>
  </p>
</div>
