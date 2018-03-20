<?php

class et_pb_pods_table extends ET_Builder_Module
{
    function init()
    {
        $this->name = esc_html__('Pods Items', 'et_builder');
        $this->slug = 'et_pb_pods_table_items';
        $this->child_slug = 'et_pb_pods_table_item';
        $this->child_item_text = esc_html__('Pods Item', 'et_builder');

        $this->whitelisted_fields = array(
            'admin_label',
            'module_id',
            'module_class',
            'default_style',
            'odd_row_colour',
            'even_row_colour',
            'odd_text_colour',
            'even_text_colour',
            'v_padding',
            'h_padding',
        );

        $this->options_toggles = array(
            'general' => array(
                'toggles' => array(
                    'main_settings' => esc_html__('Main Settings', 'et_builder'),
                ),
            ),
        );

        $this->main_css_element = '%%order_class%%.et_pb_pods_table';

        $this->advanced_options = array(
            'fonts' => array(
                'text' => array(
                    'label' => esc_html__('Content', 'et_builder'),
                    'css' => array(
                        'main' => "{$this->main_css_element} td.sb_mod_pods_table_item, {$this->main_css_element} .sb_mod_pods_table_item p",
                    ),
                    'font_size' => array('default' => '14px'),
                    'line_height' => array('default' => '1.5em'),
                ),
            ),
            'background' => array(
                'settings' => array(
                    'color' => 'alpha',
                ),
            ),
            'border' => array(),
            'custom_margin_padding' => array(
                'css' => array(
                    'important' => 'all',
                ),
            ),
        );
    }

    function get_fields()
    {
        $fields = array(
            'admin_label' => array(
                'label' => esc_html__('Admin Label', 'et_builder'),
                'type' => 'text',
                'description' => esc_html__('This will change the label of the module in the builder for easy identification.', 'et_builder'),
            ),
            'module_id' => array(
                'label' => esc_html__('CSS ID', 'et_builder'),
                'type' => 'text',
                'option_category' => 'configuration',
                'tab_slug' => 'custom_css',
                'option_class' => 'et_pb_custom_css_regular',
            ),
            'module_class' => array(
                'label' => esc_html__('CSS Class', 'et_builder'),
                'type' => 'text',
                'option_category' => 'configuration',
                'tab_slug' => 'custom_css',
                'option_class' => 'et_pb_custom_css_regular',
            ),
            'default_style' => array(
                'label' => esc_html__('Use Default Styling', 'et_builder'),
                'type' => 'yes_no_button',
                'option_category' => 'configuration',
                'options' => array(
                    'on' => esc_html__('Yes', 'et_builder'),
                    'off' => esc_html__('No', 'et_builder'),
                ),
                'affects' => array(
                    '#et_pb_odd_row_colour',
                    '#et_pb_even_row_colour',
                    '#et_pb_odd_text_colour',
                    '#et_pb_even_text_colour',
                    '#et_pb_v_padding',
                    '#et_pb_h_padding',
                ),
                'toggle_slug' => 'main_settings',
                'description' => esc_html__('This will turn on or off the detault layout for the table.', 'et_builder'),
            ),
            'odd_row_colour' => array(
                'label' => esc_html__('Odd Row Colour', 'et_builder'),
                'type' => 'color-alpha',
                'custom_color' => true,
                'depends_show_if' => 'off',
                'description' => esc_html__('Here you can define a custom color for the ODD rows in the table', 'et_builder'),
                'toggle_slug' => 'main_settings',
            ),
            'odd_text_colour' => array(
                'label' => esc_html__('Odd Text Colour', 'et_builder'),
                'type' => 'color-alpha',
                'custom_color' => true,
                'depends_show_if' => 'off',
                'description' => esc_html__('Here you can define a custom text color for the ODD rows in the table', 'et_builder'),
                'toggle_slug' => 'main_settings',
            ),
            'even_row_colour' => array(
                'label' => esc_html__('Even Row Colour', 'et_builder'),
                'type' => 'color-alpha',
                'custom_color' => true,
                'depends_show_if' => 'off',
                'description' => esc_html__('Here you can define a custom color for the EVEN rows in the table', 'et_builder'),
                'toggle_slug' => 'main_settings',
            ),
            'even_text_colour' => array(
                'label' => esc_html__('Even Text Colour', 'et_builder'),
                'type' => 'color-alpha',
                'custom_color' => true,
                'depends_show_if' => 'off',
                'description' => esc_html__('Here you can define a custom text color for the EVEN rows in the table', 'et_builder'),
                'toggle_slug' => 'main_settings',
            ),
            'v_padding' => array(
                'label' => esc_html__('Vertical Padding', 'et_builder'),
                'type' => 'text',
                'depends_show_if' => 'off',
                'mobile_options' => true,
                'validate_unit' => true,
                'toggle_slug' => 'main_settings',
            ),
            'h_padding' => array(
                'label' => esc_html__('Horizontal Padding', 'et_builder'),
                'type' => 'text',
                'depends_show_if' => 'off',
                'mobile_options' => true,
                'validate_unit' => true,
                'toggle_slug' => 'main_settings',
            ),
        );
        return $fields;
    }

    function shortcode_callback($atts, $content = null, $function_name)
    {
        if (is_admin()) {
            return;
        }

        $module_id = $this->shortcode_atts['module_id'];
        $module_class = $this->shortcode_atts['module_class'];
        $default_style = $this->shortcode_atts['default_style'];
        $odd_row_colour = $this->shortcode_atts['odd_row_colour'];
        $even_row_colour = $this->shortcode_atts['even_row_colour'];
        $odd_text_colour = $this->shortcode_atts['odd_text_colour'];
        $even_text_colour = $this->shortcode_atts['even_text_colour'];
        $vpadding = $this->shortcode_atts['v_padding'];
        $hpadding = $this->shortcode_atts['h_padding'];

        $module_class = ET_Builder_Element::add_module_order_class($module_class, $function_name) . ($default_style == 'on' ? ' et_pb_pods_table_styled' : '');

        if ($default_style == 'off') {
            if ('' !== $odd_row_colour) {
                ET_Builder_Element::set_style($function_name, array(
                    'selector' => '%%order_class%%.et_pb_pods_table tbody tr:nth-child(odd)',
                    'declaration' => sprintf(
                        'background-color: %1$s;',
                        esc_html($odd_row_colour)
                    ),
                ));
            }

            if ('' !== $even_row_colour) {
                ET_Builder_Element::set_style($function_name, array(
                    'selector' => '%%order_class%%.et_pb_pods_table tbody tr:nth-child(even)',
                    'declaration' => sprintf(
                        'background-color: %1$s;',
                        esc_html($even_row_colour)
                    ),
                ));
            }
            if ('' !== $odd_text_colour) {
                ET_Builder_Element::set_style($function_name, array(
                    'selector' => '%%order_class%%.et_pb_pods_table tbody tr:nth-child(odd) td',
                    'declaration' => sprintf(
                        'color: %1$s;',
                        esc_html($odd_text_colour)
                    ),
                ));
            }

            if ('' !== $even_text_colour) {
                ET_Builder_Element::set_style($function_name, array(
                    'selector' => '%%order_class%%.et_pb_pods_table tbody tr:nth-child(even) td',
                    'declaration' => sprintf(
                        'color: %1$s;',
                        esc_html($even_text_colour)
                    ),
                ));
            }

            if ('' !== $vpadding) {
                ET_Builder_Element::set_style($function_name, array(
                    'selector' => '%%order_class%%.et_pb_pods_table tbody tr td',
                    'declaration' => sprintf(
                        'padding-top: %1$s; padding-bottom: %1$s;',
                        esc_html($vpadding)
                    ),
                ));
            }

            if ('' !== $hpadding) {
                ET_Builder_Element::set_style($function_name, array(
                    'selector' => '%%order_class%%.et_pb_pods_table tbody tr td',
                    'declaration' => sprintf(
                        'padding-left: %1$s; padding-right: %1$s;',
                        esc_html($hpadding)
                    ),
                ));
            }
        }

        $all_tabs_content = $this->shortcode_content;

        $output = '<div ' . ('' !== $module_id ? 'id="' . esc_attr($module_id) . '" ' : '') . ' class="et_pb_module et_pb_pods_table ' . $module_class . '">
						    <table class=""><tbody>
							    ' . $all_tabs_content . '
						    </tbody></table>
						</div> <!-- .et_pt_pods_tables -->';

        return $output;
    }
}

new et_pb_pods_table();

?>