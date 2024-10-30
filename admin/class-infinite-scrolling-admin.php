<?php
class Ispfw_Infinite_Ispfw_Woo_Admin
{

    private $plugin_name;

    private $version;

    public $animation_style;

    public $infinite_sp_woo_setting_api;

    public function __construct($plugin_name, $version)
    {
        $this->infinite_sp_woo_admin_setting_page_callback();
        $this->infinite_sp_woo_setting_api = new Ispfw_Infinite_Woo_Setting_Option();
        $this->plugin_name = $plugin_name;

        $this->version = $version;

        add_action('admin_init', array($this, 'Ispfw_infinite_scroll_setting_admin_init'));
        add_action('admin_menu', array($this, 'infinite_sp_woo_add_menu'));

        add_action('wp_ajax_ispfw_export_settings', array($this, 'ispfw_export_settings'));
		add_action('wp_ajax_nopriv_ispfw_export_settings', array($this, 'ispfw_export_settings'));

		add_action('wp_ajax_ispfw_plugin_import_settings', array($this,'ispfw_plugin_import_settings'));
		add_action('wp_ajax_nopriv_ispfw_plugin_import_settings', array($this,'ispfw_plugin_import_settings'));
    }

    /**
     * Added setting page
     *
     * @since    1.0.0
     */
    public function infinite_sp_woo_admin_setting_page_callback()
    {
        include_once 'partials/infinite-scrolling-woo-admin-display.php';
    }

    public function Ispfw_infinite_scroll_setting_admin_init()
    {

        //set the settings
        $this->infinite_sp_woo_setting_api->ispfw_set_sections($this->get_infinite_sp_woo_settings_sections());
        $this->infinite_sp_woo_setting_api->ispfw_set_fields($this->get_infinite_sp_woo_settings_fields());

        //initialize settings
        $this->infinite_sp_woo_setting_api->ispfw_admin_init();
    }

    public function get_infinite_sp_woo_settings_sections()
    {
        $sections = array(
            array(
                'id'    => 'infinite_sp_woo_inf_basics',
                'title' => __('General Settings', 'infinite-scroll-woo'),
            ),
            array(
                'id'    => 'infinite_sp_woo_inf_color',
                'title' => __('Advanced Settings', 'infinite-scroll-woo'),
            ),
            array(
                'id'    => 'infinite_sp_woo_import_export',
                'title' => __('Import/Export', 'infinite-scroll-woo'),
            ),
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    public function get_infinite_sp_woo_settings_fields()
    {


        $tab_infinite_sp_woo_settings_fields = array(
            'infinite_sp_woo_inf_basics' => array(
                array(
                    'name'  => 'infinite_sp_pagination_on_off',
                    'label' => __('Status ON/OFF', 'infinite-scroll-woo'),
                    'desc'  => __('When uncheck the box then pagination off ', 'infinite-scroll-woo'),
                    'type'  => 'checkbox',
                    'default' => ''
                ),
                array(
                    'name'    => 'infinite_sp_pagination_type',
                    'label'   => __('Pagination Type', 'infinite-scroll-woo'),
                    'desc'    => __('Choose Your pagination type', 'infinite-scroll-woo'),
                    'type'    => 'select',
                    'default' => 'no',
                    'options' => array(
                        'infinite_scrolling' => 'Infinite Scroll',
                        'infinite_ajax_select'  => 'Ajax Pagination',
                        'infinite_load_more_btn'  => 'Load More'
                    )
                ),
                array(
                    'name'              => 'infinite_sp_content_selector',
                    'label'             => __('Content Selector', 'infinite-scroll-woo'),
                    'default'           => __('ul.products-block-post-template', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'name'              => 'infinite_sp_woo_item_selector',
                    'label'             => __('Loop Item Selector', 'infinite-scroll-woo'),
                    'default'           => __('li.product', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'name'              => 'infinite_sp_woo_prev_selector',
                    'label'             => __('Prev Selector', 'infinite-scroll-woo'),
                    'default'           => __('.wp-block-query-pagination', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'name'              => 'infinite_sp_woo_next_selector',
                    'label'             => __('Next Selector', 'infinite-scroll-woo'),
                    'default'           => __('.wp-block-query-pagination .wp-block-query-pagination-next', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),

                array(
                    'name'    => 'infinite_loader_image',
                    'label'   => __('Loader Image', 'infinite-scroll-woo'),
                    'desc'    => __('File description', 'infinite-scroll-woo'),
                    'type'    => 'file',
                    'default' => '',
                    'size'              => '15px',
                    'options' => array(
                        'button_label' => 'Loader Image'
                    )
                ),
                array(
                    'name'              => 'infinite_loading_btn_text',
                    'label'             => __('Loading Button Text', 'infinite-scroll-woo'),
                    'default'           => __('Loading...', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'name'              => 'infinite_load_more_btn_text',
                    'label'             => __('Load More Button Text', 'infinite-scroll-woo'),
                    'default'           => __('Load More Products', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'name'              => 'infinite_isp_per_page',
                    'label'             => __('Products Per Page', 'infinite-scroll-woo'),
                    'default'           => __('', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),

                array(
                    'name'              => 'infinite_isp_per_row_products',
                    'label'             => __('Products Per Row', 'infinite-scroll-woo'),
                    'default'           => __('', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),



            ),
            'infinite_sp_woo_inf_color'  => array(

                array(
                    'name'  => 'infinite_scroll_to_top_enable',
                    'label' => __('Scroll Top Enable?', 'infinite-scroll-woo'),
                    'desc'  => __('When uncheck the box then Scroll to Top Disable ', 'infinite-scroll-woo'),
                    'type'  => 'checkbox',
                    'default' => ''
                ),
                array(
                    'name'              => 'infinite_scroll_totop',
                    'label'             => __('Scroll To', 'infinite-scroll-woo'),
                    'default'           => __('html, body', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'name'              => 'infinite_sp_woo_buffer_pixels',
                    'label'             => __('Buffer  Pixel', 'infinite-scroll-woo'),
                    'default'           => __('50', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),

                array(
                    'name'    => 'infinite_sp_animation',
                    'label'   => __('Animation', 'infinite-scroll-woo'),
                    'desc'    => __('It Works after loading  products', 'infinite-scroll-woo'),
                    'type'    => 'select',
                    'default' => 'none',
                    'options' => $this->infinite_animation_func()
                ),

                array(
                    'name'              => 'infinite_load_more_padding',
                    'label'             => __('Load More Button Padding', 'infinite-scroll-woo'),
                    'default'           => __('12px 18px', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'name'              => 'infinite_sp_woo_border_radius',
                    'label'             => __('Button Border Radius', 'infinite-scroll-woo'),
                    'default'           => __('5px', 'infinite-scroll-woo'),
                    'type'              => 'text',
                    'size'              => '15px',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'name'  => 'isp_load_more_bg_color',
                    'label' => __('Load More Background', 'infinite-scroll-woo'),
                    'type'  => 'color',
                ),
                array(
                    'name'  => 'isp_load_more_text_color',
                    'label' => __('Load More Text', 'infinite-scroll-woo'),
                    'type'  => 'color',
                ),
                array(
                    'name'  => 'isp_load_more_border_color',
                    'label' => __('Load More Border color', 'infinite-scroll-woo'),
                    'type'  => 'color',
                ),
            ),

            'infinite_sp_woo_import_export'  => array(

                array(
                    'name'    => 'infinite_scroll_settings_export',
                    'label'   => __( 'Import/Export file', 'infinite-scroll-woo' ),
                    'desc'    => __( 'Please import your file', 'infinite-scroll-woo' ),
                    'type'    => 'file2',
                    'default' => '',
                    'options' => array(
                        'button_label' => 'Import'
                    )
                ),
               
            ),
        );

        return $tab_infinite_sp_woo_settings_fields;
    }

    public function infinite_animation_func()
	{
        $animation_array = array(
			'none'		    =>	'none',
			'bounce'		=>	'Bounce',
			'flash'			=>	'flash',
			'pulse'			=>	'pulse',
			'rubberBand'	=>	'rubberBand',
			'shake'			=>	'shake',
			'swing'			=>	'swing',
			'tada'			=>	'tada',
			'bounce'		=>	'bounce',
			'wobble'		=>	'wobble',
			'headShake'		=>	'headShake',
			'Jello'			=>	'headShake',
			'fadeIn'		=>	'Fade In',
			'fadeInDown'	=>	'Fade In Down',
			'fadeInLeft'	=>	'pulse',
			'fadeInRight'	=>	'Fade In Right',
			'fadeInUp'		=>	'Fade In Up',
			'zoomIn'		=>	'zoomIn',
			'zoomInDown'	=>	'zoomInDown',
			'zoomInLeft'	=>	'zoomInLeft',
			'zoomInRight'	=>	'zoomInRight',
			'zoomInUp'		=>	'zoomInUp',
			'bounceIn'		=>	'bounceIn',
			'bounceInDown'	=>	'bounceInDown',
			'bounceInLeft'	=>	'bounceInLeft',
			'bounceInRight'	=>	'bounceInRight',
			'bounceInUp'	=>	'bounceInUp',
			'slideInDown'	=>	'slideInDown',
			'slideInLeft'	=>	'slideInLeft',
			'slideInRight'	=>	'slideInRight',
			'slideInUp'	    =>	'slideInUp',
			'slideInDown'	=>	'slideInDown',
			'slideInLeft'	=>	'slideInLeft',
			'slideInRight'	=>	'slideInRight',
			'slideInUp'	    =>	'slideInUp',
			'rotateIn'			=>	'rotateIn',
			'rotateInDownLeft'	=>	'rotateInDownLeft',
			'rotateInDownRight'	=>	'rotateInDownRight',
			'rotateInUpLeft'	=>	'rotateInUpLeft',
			'rotateInUpRight'	=>	'rotateInUpRight',
			'lightSpeedIn'			=>	'lightSpeedIn',
			'rollIn'			=>	'rollIn',
		);

        $this->animation_style = $animation_array;

        return $this->animation_style;
	}

    // Function to handle export via AJAX
	public function ispfw_export_settings() {

        $basic_settings = get_option('infinite_sp_woo_inf_basics');

		if( is_array( $basic_settings ) ) {
			$general_option  = $basic_settings;
		} else {
			$general_option  = unserialize( $basic_settings);
		}

        $color_settings = get_option('infinite_sp_woo_inf_color');

		if( is_array( $color_settings ) ) {
			$advanced_option  = $color_settings;
		} else {
			$advanced_option  = unserialize( $color_settings);
		}
	

		$settings_data = [
			'infinite_sp_pagination_on_off' => $general_option['infinite_sp_pagination_on_off'],
			'infinite_sp_pagination_type' => $general_option['infinite_sp_pagination_type'],
			'infinite_sp_content_selector' => $general_option['infinite_sp_content_selector'],
			'infinite_sp_woo_item_selector' => $general_option['infinite_sp_woo_item_selector'],
			'infinite_sp_woo_prev_selector' => $general_option['infinite_sp_woo_prev_selector'],
			'infinite_sp_woo_next_selector' => $general_option['infinite_sp_woo_next_selector'],
			'infinite_loader_image' => $general_option['infinite_loader_image'],
			'infinite_loading_btn_text' => $general_option['infinite_loading_btn_text'],
			'infinite_load_more_btn_text' => $general_option['infinite_load_more_btn_text'],
			'infinite_isp_per_row_products' => $general_option['infinite_isp_per_row_products'],
			'infinite_isp_per_page' => $general_option['infinite_isp_per_page'],
			'infinite_scroll_to_top_enable' => $advanced_option['infinite_scroll_to_top_enable'],
			'infinite_scroll_totop' => $advanced_option['infinite_scroll_totop'],
			'infinite_sp_woo_buffer_pixels' => $advanced_option['infinite_sp_woo_buffer_pixels'],
			'infinite_sp_animation' => $advanced_option['infinite_sp_animation'],
			'infinite_load_more_padding' => $advanced_option['infinite_load_more_padding'],
			'infinite_sp_woo_border_radius' => $advanced_option['infinite_sp_woo_border_radius'],
			'isp_load_more_bg_color' => $advanced_option['isp_load_more_bg_color'],
			'isp_load_more_text_color' => $advanced_option['isp_load_more_text_color'],
			'isp_load_more_border_color' => $advanced_option['isp_load_more_border_color'],
		];

		if ($settings_data) {
			
			header('Content-Disposition: attachment; filename="plugin-settings.json"');
			header('Content-Type: application/json; charset=utf-8');

			echo ( json_encode($settings_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );
			wp_die(); 

		} else {
			wp_send_json_error('No settings found to export.');
		}
	}

	// Function to handle the import via AJAX
	public function ispfw_plugin_import_settings() {

        if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['security'] ) ) , 'ispfw_export_nonce' ) ) {
            wp_send_json_error('Invalid nonce');
            exit;
        }

		// Check if a file has been uploaded
		if ( !empty($_FILES['import_file']['tmp_name'] ) ) {

			$json_data = file_get_contents($_FILES['import_file']['tmp_name']);
			$settings = json_decode($json_data, true);

			// Validate if it's a proper array of settings
			if (is_array($settings)) {

				// Extract and prepare settings
				$basic_settings = [
					'infinite_sp_pagination_on_off'      => sanitize_ispfw_custom_field_items_data( $settings['infinite_sp_pagination_on_off'] ),
					'infinite_sp_pagination_type'        => sanitize_ispfw_custom_field_items_data( $settings['infinite_sp_pagination_type']),
					'infinite_sp_content_selector'       => sanitize_ispfw_custom_field_items_data( $settings['infinite_sp_content_selector']),
					'infinite_sp_woo_item_selector'      => sanitize_ispfw_custom_field_items_data( $settings['infinite_sp_woo_item_selector']),
					'infinite_sp_woo_prev_selector'      => sanitize_ispfw_custom_field_items_data( $settings['infinite_sp_woo_prev_selector']),
					'infinite_sp_woo_next_selector'      => sanitize_ispfw_custom_field_items_data( $settings['infinite_sp_woo_next_selector']),
					'infinite_loader_image'              => sanitize_ispfw_custom_field_items_data( $settings['infinite_loader_image']),
					'infinite_loading_btn_text'          => sanitize_ispfw_custom_field_items_data( $settings['infinite_loading_btn_text']),
					'infinite_load_more_btn_text'        => sanitize_ispfw_custom_field_items_data( $settings['infinite_load_more_btn_text']),
					'infinite_isp_per_row_products'      => sanitize_ispfw_custom_field_items_data( $settings['infinite_isp_per_row_products']),
					'infinite_isp_per_page'              => sanitize_ispfw_custom_field_items_data( $settings['infinite_isp_per_page']),
				];

				$color_settings = [
					'infinite_scroll_to_top_enable'      => sanitize_ispfw_custom_field_items_data( $settings['infinite_scroll_to_top_enable']),
					'infinite_scroll_totop'              => sanitize_ispfw_custom_field_items_data( $settings['infinite_scroll_totop']),
					'infinite_sp_woo_buffer_pixels'      => sanitize_ispfw_custom_field_items_data( $settings['infinite_sp_woo_buffer_pixels']),
					'infinite_sp_animation'              => sanitize_ispfw_custom_field_items_data( $settings['infinite_sp_animation']),
					'infinite_load_more_padding'         => sanitize_ispfw_custom_field_items_data( $settings['infinite_load_more_padding']),
					'infinite_sp_woo_border_radius'      => sanitize_ispfw_custom_field_items_data( $settings['infinite_sp_woo_border_radius']),
					'isp_load_more_bg_color'             => sanitize_ispfw_custom_field_items_data( $settings['isp_load_more_bg_color']),
					'isp_load_more_text_color'           => sanitize_ispfw_custom_field_items_data( $settings['isp_load_more_text_color']),
					'isp_load_more_border_color'         => sanitize_ispfw_custom_field_items_data( $settings['isp_load_more_border_color']),
				];

				$serialized_basic_settings = serialize($basic_settings);
    			$serialized_color_settings = serialize($color_settings);

				// Update options in one go
				update_option('infinite_sp_woo_inf_basics',  $serialized_basic_settings );
				update_option('infinite_sp_woo_inf_color', $serialized_color_settings );


				wp_send_json_success('Settings imported successfully.');

			} else {
				wp_send_json_error('Invalid settings format.');
			}


		} else {

			wp_send_json_error('No file uploaded.');

		}

		wp_die(); // Always end the AJAX handler
	}

    /**
     * add admin menu
     *
     * @since    1.0.0
     */
    public function infinite_sp_woo_add_menu()
    {

        add_menu_page(
            __('Infinite Scrolling', 'infinite-scroll-woo'),
            __('Infinite Scrolling', 'infinite-scroll-woo'),
            'manage_options',
            'infinite-scrolling-option-setting',
            array($this, 'infinite_sp_woo_menu_callback'),
            'dashicons-layout',
            "111"
        );
    }

    public function infinite_sp_woo_menu_callback()
    { ?>
        <div class="cdt-wrap">
            <?php
            $this->infinite_sp_woo_setting_api->ispfw_infinite_sp_woo_show_navigation();
            $this->infinite_sp_woo_setting_api->ispfw_infinite_scrolling_show_forms();
            ?>

        </div>
<?php }

    public function infinite_sp_woo_enqueue_styles()
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style( 'ispow_woo_admin_css', ISPFW_WOO_ADMIN_URL . '/assets/css/ispfw-admin.css', array(), $this->version );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function infinite_sp_woo_enqueue_scripts()
    {

        wp_enqueue_script('wp-color-picker');
        wp_enqueue_media();

        wp_enqueue_script('ispfwajax-admin-script', ISPFW_WOO_ADMIN_URL . 'assets/js/ispfw-admin.js', array('jquery'), $this->version, true);

        // Localize script to pass the AJAX URL and nonce
        wp_localize_script('ispfwajax-admin-script', 'ispfwAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('ispfw_export_nonce'),
        ));
    }
}
