<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/faridmia/
 * @since      1.0.0
 *
 * @package    Infinite_Ispfw_Woo
 * @subpackage Infinite_Ispfw_Woo/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Infinite_Ispfw_Woo
 * @subpackage Infinite_Ispfw_Woo/public
 * @author     Farid Mia <mdfarid7830@gmail.com>
 */
class Ispfw_Infinite_Woo_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $infinite_sp_woo    The ID of this plugin.
	 */
	private $infinite_sp_woo;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $infinite_sp_woo       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $infinite_sp_woo, $version ) {

		$this->infinite_sp_woo = $infinite_sp_woo;
		$this->version = $version;

		

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ispfw_Infinite_Woo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ispfw_Infinite_Woo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_style( $this->infinite_sp_woo, ISPFW_WOO_PUBLIC_URL . 'css/advanced-infinite-scroll-woo-public.css', array(), $this->version, 'all' );

	}

	

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ispfw_Infinite_Woo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ispfw_Infinite_Woo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}

}
