<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  
if (!class_exists('WooCommerce')) {
    return false;
}

class Ispfw_Infinite_Woo_Hooks_Function
{

    public function __construct()
    {
    }

    public function Ispfw_add_custom_css_callback()
    {
        global $advanced_option;
        
        if(isset($advanced_option)) {

            $infinite_load_more_padding = (isset($advanced_option['infinite_load_more_padding'])) ? $advanced_option['infinite_load_more_padding'] : '';
            $isp_load_more_bg_color     = (isset($advanced_option['isp_load_more_bg_color'])) ? $advanced_option['isp_load_more_bg_color'] : '';
            $isp_load_more_text_color   = (isset($advanced_option['isp_load_more_text_color'])) ? $advanced_option['isp_load_more_text_color'] : '';
            $isp_load_more_border_color = (isset($advanced_option['isp_load_more_border_color'])) ? $advanced_option['isp_load_more_border_color'] : '';
            $infinite_sp_woo_border_radius = (isset($advanced_option['infinite_sp_woo_border_radius'])) ? $advanced_option['infinite_sp_woo_border_radius'] : '';
        }


        $custom_style = '';

        if ($infinite_load_more_padding != "" || $isp_load_more_bg_color != "" || $isp_load_more_text_color != "" || $isp_load_more_border_color != "" || $infinite_sp_woo_border_radius != "") {
            $custom_style .= ".isp-infinite-scroll-load-more a {
                padding: $infinite_load_more_padding;
                background-color: $isp_load_more_bg_color;
                color: $isp_load_more_text_color;
                border-color: $isp_load_more_border_color;
                border-radius: $infinite_sp_woo_border_radius;
            }";
        }

        wp_register_style('infinite_scrolling_custom_css_button', false);
        wp_enqueue_style('infinite_scrolling_custom_css_button');
        wp_add_inline_style('infinite_scrolling_custom_css_button', $custom_style);
     }
}