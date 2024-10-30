<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/Faridmia/infinite-scroll-product-for-woocommerce
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
class Ispfw_Infinite_Woo_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $infinite_sp_woo    The ID of this plugin.
	 */
	private $plugin_name;

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_filter('loop_shop_columns', [$this,'Ispfw_infinite_products_loop_columns'], 99);


		$setting = $this->Ispfw_infinite_scroll_setting();
		$setting2 = $this->Ispfw_infinite_scroll_advanced_setting();

		global $advanced_option, $general_option;
		$general_option = $setting;

		$advanced_option = $setting2;

		$callback = function( $products ) {
			global $general_option;
			if(isset($general_option['infinite_isp_per_page'])) {
				$products = $general_option['infinite_isp_per_page'];
			}
			
			return $products;
		};

		add_filter( 'loop_shop_per_page', $callback  , 30 );

		return [$general_option, $advanced_option];

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

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

		wp_enqueue_style($this->plugin_name, ISPFW_WOO_PUBLIC_URL . 'css/infinite-scrolling-woo-public.css', array(), $this->version, 'all');
	}


	public function Ispfw_infinite_scroll_setting()
	{
		$basic_settings = get_option('infinite_sp_woo_inf_basics');

		if( is_array( $basic_settings ) ) {
			$settings  = $basic_settings;
		} else {
			$settings  = unserialize( $basic_settings);
		}

		return $settings;
	}

	public function Ispfw_infinite_scroll_advanced_setting()
	{
		$color_settings = get_option('infinite_sp_woo_inf_color');

		if( is_array( $color_settings ) ) {
			$settings  = $color_settings;
		} else {
			$settings  = unserialize( $color_settings);
		}
		return $settings;
	}


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		$general_option = $this->Ispfw_infinite_scroll_setting();
		$advanced_option = $this->Ispfw_infinite_scroll_advanced_setting();

		wp_enqueue_script( $this->plugin_name, ISPFW_WOO_PUBLIC_URL . 'js/infinite-scrolling-woo-public.js', array( 'jquery' ), $this->version, false );
        wp_localize_script($this->plugin_name, 'ispfw_option_data', array(
            "general_option"=> $general_option,
            "advanced_option"=> $advanced_option,
            )
        );
		

	}

	/**
	 * Override theme default specification for product # per row
	 */
	public function Ispfw_infinite_products_loop_columns() {
		global $general_option;
		$products_per_column = '';
		if(isset($general_option['infinite_isp_per_row_products'])) {
			$products_per_column = $general_option['infinite_isp_per_row_products'];
		}
		return $products_per_column; // 5 products per row
		
	}
}

?>

