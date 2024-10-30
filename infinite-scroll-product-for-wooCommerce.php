<?php

/**
 * Plugin Name:       Infinite Scroll Product For WooCommerce
 * Plugin URI:        https://github.com/Faridmia/infinite-scroll-product-for-woocommerce
 * Description:       The Infinite Scroll Product For WooCommerce is a powerful tool designed to enhance the browsing experience and improve the performance of online stores built with WooCommerce. It provides seamless and dynamic scrolling functionality, allowing customers to browse through product listings without the need for traditional pagination.
 * Version:           1.0.2
 * Author:            faridmia
 * Author URI:        https://profiles.wordpress.org/faridmia/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       infinite-scroll-woo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Defines CONSTANTS for Whole plugins.
 */
define('ISPFW_WOO_FILE', __FILE__);
define('ISPFW_WOO_VERSION', '1.0.0');
define('ISPFW_WOO_URL', plugins_url('/', __FILE__));
define('ISPFW_WOO_PATH', plugin_dir_path(__FILE__));
define('ISPFW_WOO_DIR_URL', plugin_dir_url(__FILE__));
define('ISPFW_WOO_BASENAME', plugin_basename(__FILE__));

define('ISPFW_WOO_ASSETS', ISPFW_WOO_URL);
define('ISPFW_WOO_ASSETS_PATH', ISPFW_WOO_PATH);
define('ISPFW_WOO_INCLUDES', ISPFW_WOO_PATH . 'includes/');

define('ISPFW_WOO_ADMIN_URL', ISPFW_WOO_ASSETS . 'admin/');
define('ISPFW_WOO_PUBLIC_URL', ISPFW_WOO_ASSETS . 'public/');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-advanced-infinite-scroll-woo-activator.php
 */
function ispfw_activate_func()
{
	require_once ISPFW_WOO_PATH . 'includes/class-infinite-scrolling-woo-activator.php';
	Ispfw_Woo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-advanced-infinite-scroll-woo-deactivator.php
 */
function ispfw__deactivate_func()
{
	require_once ISPFW_WOO_PATH . 'includes/class-infinite-scrolling-woo-deactivator.php';
	Ispfw_Woo_Deactivator::deactivate();
}

register_activation_hook(ISPFW_WOO_FILE, 'ispfw_activate_func');
register_deactivation_hook(ISPFW_WOO_FILE, 'ispfw__deactivate_func');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require ISPFW_WOO_PATH . 'includes/class-infinite-scrolling-woo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function ispfw__run_func()
{

	$plugin = new Ispfw_Woo_Infinite();
	$plugin->run();
}

function ispfw_admin_notices()
{ ?>
	<div class="error">
		<p><?php echo wp_kses('<strong>Infinite Scroll Product For WooCommerce  requires WooCommerce to be installed and active. You can download <a href="https://woocommerce.com/" class="message" target="_blank">WooCommerce</a> here.</strong>', 'ispfw_kses'); ?></p>
	</div>
<?php
}
// woocommerce  plugin dependency
function Ispfw_woo_install_woocommerce_dependency()
{
	if (!function_exists('WC')) {
		add_action('admin_notices', 'ispfw_admin_notices');
	}
}

add_action('plugins_loaded',  'Ispfw_woo_install_woocommerce_dependency');

ispfw__run_func();