<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/Faridmia/infinite-scroll-product-for-woocommerce
 * @since      1.0.0
 *
 * @package    Infinite_Ispfw_Woo
 * @subpackage Infinite_Ispfw_Woo/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Infinite_Ispfw_Woo
 * @subpackage Infinite_Ispfw_Woo/includes
 * @author     Farid Mia <mdfarid7830@gmail.com>
 */
class Ispfw_Infinite_Woo_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'infinite-scroll-woo',
			false,
			ISPFW_WOO_BASENAME . '/languages/'
		);

	}



}
