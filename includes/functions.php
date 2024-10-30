<?php
// // Silence is golden

function ispfw_kses_allowed_html($ispfw_tags, $ispfw_context)
{
    switch ($ispfw_context) {
        case 'ispfw_kses':
            $ispfw_tags = array(
                'div'    => array(
                    'class' => array(),
                ),
                'ul'     => array(
                    'class' => array(),
                ),
                'li'     => array(),
                'span'   => array(
                    'class' => array(),
                ),
                'a'      => array(
                    'href'  => array(),
                    'class' => array(),
                    'target' => array(),
                    'id' => true,
                ),
                'i'      => array(
                    'class' => array(),
                ),
                'p'      => array(),
                'em'     => array(),
                'br'     => array(),
                'strong' => array(),
                'h1'     => array(),
                'h2'     => array(),
                'h3'     => array(),
                'h4'     => array(),
                'h5'     => array(),
                'h6'     => array(),
                'del'    => array(),
                'ins'    => array(),
                'input' => array(
                    'type' => true,
                    'class' => true,
                    'id' => true,
                    'name' => true,
                    'value' => true,
                    'data-default-color' => array(),
                    'checked' => true,
                ),
                'select' => array(
                    'name' => true,
                    'id'   => true,
                ),
                'option' => array(
                    'value'    => true,
                    'selected' => true,
                ),
            );
            return $ispfw_tags;
        default:
            return $ispfw_tags;
    }
}

add_filter('wp_kses_allowed_html', 'ispfw_kses_allowed_html', 10, 2);

function sanitize_ispfw_custom_field_items_data($data)
{
    $sanitized_data = array();

    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $sanitized_key = sanitize_key($key);
            $sanitized_value = array_map('sanitize_text_field', $value);
            $sanitized_data[$sanitized_key] = $sanitized_value;
        }
    } else {
        $sanitized_data = sanitize_text_field($data);
    }

    return $sanitized_data;
}
