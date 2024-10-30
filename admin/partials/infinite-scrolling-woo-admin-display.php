<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/Faridmia/infinite-scroll-product-for-woocommerce
 * @since      1.0.0
 *
 * @package    infinite_ispfw_woo
 * @subpackage infinite_ispfw_woo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    infinite_ispfw_woo
 * @subpackage infinite_ispfw_woo/admin
 * @author     faridmia
 */
if ( !class_exists( 'Ispfw_Infinite_Woo_Setting_Option' ) ):
    class Ispfw_Infinite_Woo_Setting_Option {

        /**
         * settings sections array
         *
         * @var array
         */
        protected $infinite_sp_woo_settings_sections = array();

        /**
         * Settings fields array
         *
         * @var array
         */
        protected $infinite_sp_woo_settings_fields = array();

        /**
         * Set settings sections
         *
         * @param array   $sections setting sections array
         */
        public function ispfw_set_sections( $sections ) {
            $this->infinite_sp_woo_settings_sections = $sections;

            return $this;
        }

        /**
         * Add a single section
         *
         * @param array   $section
         */
        public function ispfw_add_section( $section ) {
            $this->infinite_sp_woo_settings_sections[] = $section;
            return $this;
        }

        /**
         * Set settings fields
         *
         * @param array   $fields settings fields array
         */
        public function ispfw_set_fields( $fields ) {
            $this->infinite_sp_woo_settings_fields = $fields;
            return $this;
        }

        public function ispfw_add_field( $section, $field ) {
            $defaults = array(
                'name'  => '',
                'label' => '',
                'desc'  => '',
                'type'  => 'text',
            );

            $arg = wp_parse_args( $field, $defaults );
            $this->infinite_sp_woo_settings_fields[$section][] = $arg;

            return $this;
        }

        /**
         * Initialize and registers the settings sections and fileds to WordPress
         *
         * Usually this should be called at `admin_init` hook.
         */
        public function ispfw_admin_init() {
            //register settings sections

            foreach ( $this->infinite_sp_woo_settings_sections as $section ) {

                if ( false == get_option( $section['id'] ) ) {
                    add_option( $section['id'] );
                }

                if ( isset( $section['desc'] ) && !empty( $section['desc'] ) ) {
                    $section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
                    $callback = function () use ( $section ) {
                        echo wp_kses_post( $section['desc'] );
                    };
                } else if ( isset( $section['callback'] ) ) {
                $callback = $section['callback'];
            } else {
                $callback = null;
            }

            add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
        }

        //register settings fields

        foreach ( $this->infinite_sp_woo_settings_fields as $section => $field ) {
            foreach ( $field as $option ) {

                $name = $option['name'];
                $type = isset( $option['type'] ) ? $option['type'] : 'text';
                $label = isset( $option['label'] ) ? $option['label'] : '';
                $callback = isset( $option['callback'] ) ? $option['callback'] : array( $this, 'callback_' . $type );

                $args = array(
                    'id'                => $name,
                    'class'             => isset( $option['class'] ) ? $option['class'] : $name,
                    'label_for'         => "{$section}[{$name}]",
                    'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
                    'name'              => $label,
                    'section'           => $section,
                    'size'              => isset( $option['size'] ) ? $option['size'] : null,
                    'options'           => isset( $option['options'] ) ? $option['options'] : '',
                    'std'               => isset( $option['default'] ) ? $option['default'] : '',
                    'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
                    'type'              => $type,
                    'placeholder'       => isset( $option['placeholder'] ) ? $option['placeholder'] : '',
                    'min'               => isset( $option['min'] ) ? $option['min'] : '',
                    'max'               => isset( $option['max'] ) ? $option['max'] : '',
                    'step'              => isset( $option['step'] ) ? $option['step'] : '',
                );

                add_settings_field( "{$section}[{$name}]", $label, $callback, $section, $section, $args );
            }
        }
        // creates our settings in the options table
        foreach ( $this->infinite_sp_woo_settings_sections as $section ) {
            register_setting( $section['id'], $section['id'], array( $this, 'ispfw_sanitize_options' ) );
        }
    }

    /**
     * Get field description for display
     *
     * @param array   $args settings field args
     */
    public function ispfw_get_field_description( $args ) {

        if ( !empty( $args['desc'] ) ) {
            $desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
        } else {
            $desc = '';
        }

        return $desc;
    }

    /**
     * Displays a text field for a settings field
     *
     * @param array   $args settings field args
     */
    public function callback_text( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $type = isset( $args['type'] ) ? $args['type'] : 'text';
        $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

        $html = sprintf(
            '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>',
            esc_attr( $type ),
            esc_attr( $size ),
            esc_attr( $args['section'] ),
            esc_attr( $args['id'] ),
            esc_attr( $value ),
            esc_attr( $placeholder ) // Escape $placeholder for consistency
        );

        $html .= $this->ispfw_get_field_description( $args );

        echo wp_kses( $html, 'ispfw_kses' );
    }

    /**
     * Displays a checkbox for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_checkbox( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );

        $html = '<fieldset>';
        $html = sprintf(
            '<label for="wpuf-%1$s[%2$s]">',
            esc_attr( $args['section'] ),
            esc_attr( $args['id'] )
        );

        $html .= sprintf(
            '<input type="hidden" name="%1$s[%2$s]" value="off" />',
            esc_attr( $args['section'] ),
            esc_attr( $args['id'] )
        );

        $html .= sprintf(
            '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />',
            esc_attr( $args['section'] ),
            esc_attr( $args['id'] ),
            checked( $value, 'on', false )
        );

        $html .= sprintf(
            '%1$s</label>',
            esc_html( $args['desc'] ) // Escape $args['desc'] for safe display
        );

        $html .= '</fieldset>';

        echo wp_kses( $html, 'ispfw_kses' );
    }

    /**
     * Displays a selectbox for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_select( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

        $html = sprintf(
            '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">',
            esc_attr( $size ),
            esc_attr( $args['section'] ),
            esc_attr( $args['id'] )
        );

        foreach ( $args['options'] as $key => $label ) {
            $html .= sprintf(
                '<option value="%s"%s>%s</option>',
                esc_attr( $key ),
                selected( $value, $key, false ),
                esc_html( $label ) // Escape $label for safe display
            );
        }

        $html .= '</select>';
        $html .= $this->ispfw_get_field_description( $args );

        echo wp_kses( $html, 'ispfw_kses' );
    }

    /**
     * Displays a url field for a settings field
     *
     * @param array   $args settings field args
     */
    public function ispfw_callback_url( $args ) {
        $this->ispfw_callback_text( $args );
    }

    /**
     * Displays the html for a settings field
     *
     * @param array   $args settings field args
     * @return string
     */
    public function ispfw_callback_html( $args ) {
        echo wp_kses_post( $this->ispfw_get_field_description( $args ) );
    }

    /**
     * Displays a color picker field for a settings field
     *
     * @param array   $args settings field args
     */
    public function callback_color( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

        $html = sprintf(
            '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />',
            esc_attr( $size ),
            esc_attr( $args['section'] ),
            esc_attr( $args['id'] ),
            esc_attr( $value ),
            esc_attr( $args['std'] )
        );

        $html .= $this->ispfw_get_field_description( $args );

        echo wp_kses( $html, 'ispfw_kses' );
    }

    /**
     * Displays a select box for creating the pages select box
     *
     * @param array   $args settings field args
     */
    public function ispfw_callback_pages( $args ) {

        $dropdown_args = array(
            'selected' => esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) ),
            'name'     => $args['section'] . '[' . $args['id'] . ']',
            'id'       => $args['section'] . '[' . $args['id'] . ']',
            'echo'     => 0,
        );
        $html = wp_dropdown_pages( $dropdown_args );
        echo wp_kses( $html, 'ispfw_kses' );
    }

    /**
     * Sanitize callback for Settings API
     *
     * @return mixed
     */
    public function ispfw_sanitize_options( $options ) {
        if ( !$options ) {
            return $options;
        }

        if(is_array($options) || is_object($options) ) {
            foreach ( $options as $option_slug => $option_value ) {
                $sanitize_callback = $this->ispfw_get_sanitize_callback( $option_slug );

                // If callback is set, call it
                if ( $sanitize_callback ) {
                    $options[$option_slug] = call_user_func( $sanitize_callback, $option_value );
                    continue;
                }
            }
        }

        return $options;
    }

    /**
     * Get sanitization callback for given option slug
     *
     * @param string $slug option slug
     *
     * @return mixed string or bool false
     */
    public function ispfw_get_sanitize_callback( $slug = '' ) {
        if ( empty( $slug ) ) {
            return false;
        }

        // Iterate over registered fields and see if we can find proper callback
        foreach ( $this->infinite_sp_woo_settings_fields as $section => $options ) {
            foreach ( $options as $option ) {
                if ( $option['name'] != $slug ) {
                    continue;
                }
                // Return the callback name
                return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
            }
        }

        return false;
    }

    /**
     * Get the value of a settings field
     *
     * @param string  $option  settings field name
     * @param string  $section the section name this field belongs to
     * @param string  $default default text if it's not found
     * @return string
     */
    public function get_option( $option, $section, $default = '' ) {
        $options = get_option( $section );

        if(is_array($options)) {
            $datas = $options;
        } else {
            $datas = unserialize( $options);
        }

        if ( isset( $datas[$option] ) ) {
            return $datas[$option];
        }

        return $default;
    }

    /**
     * Displays a file upload field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_file( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $id = $args['section'] . '[' . $args['id'] . ']';
        $label = isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : __( 'Choose File' );

        $html = sprintf(
            '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>',
            esc_attr( $size ),
            esc_attr( $args['section'] ),
            esc_attr( $args['id'] ),
            esc_attr( $value )
        );

        $html .= sprintf(
            '<input type="button" class="button wpsa-browse" value="%s" />',
            esc_attr( $label ) // Escape the button label
        );

        $html .= $this->ispfw_get_field_description( $args );

        echo wp_kses( $html, 'ispfw_kses' );
    }

    function callback_file2( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $id = $args['section'] . '[' . $args['id'] . ']';
        $label = isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : __( 'Import', 'my-plugin-textdomain' );

        $html = '<div class="ispow-export-settings-field"><input type="hidden" name="ispow_export_settings_action" value="export_settings" />
            <input id="export-settings-button" type="submit" class="button button-secondary" value="Export Settings" /></div>';

        $html .= '<div class="ispfw_file_input_part">';
        $html .= '<input type="hidden" name="ispfw_settings_import_action" value="import_settings" />';

        $html .= '<input type="file" id="ispfw_settings_import_file" name="ispfw_settings_import_file" />';
        $html .= '</div><div class="ispfw_file_button_part">';
        $html .= sprintf(
            '<input type="submit" class="button button-secondary ispfw_settings_import" value="%s" /></div>',
            esc_attr__( 'Import Settings', 'my-plugin-textdomain' )
        );

        $html .= $this->ispfw_get_field_description( $args );

        printf("%s", $html);

    }

    /**
     * Show navigations as tab
     *
     * Shows all the settings section labels as tab
     */
    public function ispfw_infinite_sp_woo_show_navigation() {
        $html = '<h2 class="nav-tab-wrapper">';

        $count = count( $this->infinite_sp_woo_settings_sections );
        if ( $count === 1 ) {
            return;
        }
        foreach ( $this->infinite_sp_woo_settings_sections as $tab ) {
            $html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
        }

        $html .= '</h2>';

        echo wp_kses_post( $html );
    }

    /**
     * Show the section settings forms
     *
     * This function displays every sections in a different form
     */
    public function ispfw_infinite_scrolling_show_forms() {
        ?>
            <div class="metabox-holder lasf-woo-metabox-holder">
                <?php foreach ( $this->infinite_sp_woo_settings_sections as $form ) {

            ?>
                    <div id="<?php echo esc_attr( $form['id'] ); ?>" class="group lasf-woo">
                        <form method="post" action="options.php">
                            <?php
do_action( 'wsa_form_top_' . esc_attr( $form['id'] ), $form );
            settings_fields( wp_kses_post( $form['id'] ) );
            do_settings_sections( wp_kses_post( $form['id'] ) );
            do_action( 'wsa_form_bottom_' . esc_attr( $form['id'] ), $form );
            if ( isset( $this->infinite_sp_woo_settings_fields[$form['id']] ) ):

                if ( $form['id'] != 'infinite_sp_woo_import_export' ) {
                    ?>

	                                <div class="lasf-woo">
	                                    <?php submit_button();?>
	                                </div>
	                            <?php
    }endif;?>
                        </form>
                    </div>
                <?php
}?>
            </div>
        <?php
$this->ispfw_script();
    }
    /**
     * Tabbable JavaScript codes & Initiate Color Picker
     *
     * This code uses localstorage for displaying active tabs
     */
    public function ispfw_script() {
        ?>
            <script>
                jQuery(document).ready(function($) {
                    //Initiate Color Picker
                    $('.wp-color-picker-field').wpColorPicker();

                    // Switches option sections
                    $('.group').hide();
                    var activetab = '';
                    if (typeof(localStorage) != 'undefined') {
                        activetab = localStorage.getItem("activetab");
                    }

                    //if url has section id as hash then set it as active or override the current local storage value
                    if (window.location.hash) {
                        activetab = window.location.hash;
                        if (typeof(localStorage) != 'undefined') {
                            localStorage.setItem("activetab", activetab);
                        }
                    }

                    if (activetab != '' && $(activetab).length) {
                        $(activetab).fadeIn();
                    } else {
                        $('.group:first').fadeIn();
                    }
                    $('.group .collapsed').each(function() {
                        $(this).find('input:checked').parent().parent().parent().nextAll().each(
                            function() {
                                if ($(this).hasClass('last')) {
                                    $(this).removeClass('hidden');
                                    return false;
                                }
                                $(this).filter('.hidden').removeClass('hidden');
                            });
                    });

                    if (activetab != '' && $(activetab + '-tab').length) {
                        $(activetab + '-tab').addClass('nav-tab-active');
                    } else {
                        $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
                    }
                    $('.nav-tab-wrapper a').click(function(evt) {
                        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                        $(this).addClass('nav-tab-active').blur();
                        var clicked_group = $(this).attr('href');
                        if (typeof(localStorage) != 'undefined') {
                            localStorage.setItem("activetab", $(this).attr('href'));
                        }
                        $('.group').hide();
                        $(clicked_group).fadeIn();
                        evt.preventDefault();
                    });

                    $('.wpsa-browse').on('click', function(event) {
                        event.preventDefault();

                        var self = $(this);

                        // Create the media frame.
                        var file_frame = wp.media.frames.file_frame = wp.media({
                            title: self.data('uploader_title'),
                            button: {
                                text: self.data('uploader_button_text'),
                            },
                            multiple: false
                        });

                        file_frame.on('select', function() {
                            attachment = file_frame.state().get('selection').first().toJSON();
                            self.prev('.wpsa-url').val(attachment.url).change();
                        });

                        // Finally, open the modal
                        file_frame.open();
                    });
                });
            </script>
<?php
}
}

endif;
