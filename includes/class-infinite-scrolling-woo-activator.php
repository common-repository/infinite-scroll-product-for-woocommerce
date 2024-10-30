<?php
/**
 * Fired during plugin activation
 *
 * @link       https://github.com/Faridmia/infinite-scroll-product-for-woocommerce
 * @since      1.0.0
 *
 * @package    Infinite_Ispfw_Woo
 * @subpackage Infinite_Ispfw_Woo/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Infinite_Ispfw_Woo
 * @subpackage Infinite_Ispfw_Woo/includes
 * @author     Farid Mia <mdfarid7830@gmail.com>
 */
class Ispfw_Woo_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        if ( !class_exists( 'WooCommerce' ) ) {
            return false;
        }
    }

}
