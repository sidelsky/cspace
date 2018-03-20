<?php

class et_pb_pods_single extends ET_Builder_Module
{
    function init()
    {
        $this->name = esc_html__('Pods Single Item', 'et_builder');
        $this->slug = 'et_pb_pods_single_item';

        $this->whitelisted_fields = array(
            'module_id',
            'module_class',
            'field_name',
            'image_size',
            'format_output',
            'title',
            'link_image'
        );

        $this->options_toggles = array(
            'general' => array(
                'toggles' => array(
                    'main_settings' => esc_html__('Main Settings', 'et_builder'),
                ),
            ),
        );

        $this->fields_defaults = array();
        //$this->main_css_element = '.et_pb_pods_single';
        $this->main_css_element = '%%order_class%%';

        $this->advanced_options = array(
            'fonts' => array(
                'text' => array(
                    'label' => esc_html__('Value', 'et_builder'),
                    'css' => array(
                        'main' => "{$this->main_css_element} .sb_mod_pods_single_item p, {$this->main_css_element} .sb_mod_pods_single_item",
                    ),
                    'font_size' => array('default' => '14px'),
                    'line_height' => array('default' => '1.5em'),
                ),
                'headings' => array(
                    'label' => esc_html__('Headings', 'et_builder'),
                    'css' => array(
                        'main' => "{$this->main_css_element} h2.pods_label",
                    ),
                    'font_size' => array('default' => '30px'),
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
        $options = sb_mod_pods_get_fields();

        $fields = array(
            'title' => array(
                'label' => esc_html__('Title', 'et_builder'),
                'type' => 'text',
                'description' => esc_html__('The label that will be used for this field on the front end. (Optional)', 'et_builder'),
                'toggle_slug' => 'main_settings',
            ),
            'field_name' => array(
                'label' => __('Field', 'et_builder'),
                'type' => 'select',
                'options' => $options,
                'description' => __('Pick which field to show.', 'et_builder'),
                'toggle_slug' => 'main_settings',
            ),
            'format_output' => array(
                'label' => __('Output Format', 'et_builder'),
                'type' => 'select',
                'options' => array('none' => 'None', 'autop' => 'Add Paragraphs', 'audio' => 'Show Audio Player', 'video' => 'Show Video Player'),
                'description' => __('How should the output be formatted? None is default.', 'et_builder'),
                'toggle_slug' => 'main_settings',
            ),
            'admin_label' => array(
                'label' => esc_html__('Admin Label', 'et_builder'),
                'type' => 'text',
                'description' => esc_html__('This will change the label of the module in the builder for easy identification.', 'et_builder'),
                'toggle_slug' => 'main_settings',
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
        $field = $this->shortcode_atts['field_name'];

        $title = $this->shortcode_atts['title'];

        $module_class = ET_Builder_Element::add_module_order_class($module_class, $function_name);

        //////////////////////////////////////////////////////////////////////

        $output = '';
        $content = '';

        if ($field_arr = explode('|', $field)) {
            $field = $field_arr[1];
        }

        $pods_post = get_post($field);

        if (get_post_meta(get_the_ID(), $pods_post->post_name, true)) {

            if ($title) {
                $content .= '<h2 class="pods_label">' . $title . '</h2>';
            }

            $value = sb_mod_pods_parse_value_by_type($field);

            if ($this->shortcode_atts['format_output']) {
                switch ($this->shortcode_atts['format_output']) {
                    case 'autop':
                        $value = wpautop($value);
                        break;
                    case 'audio':
                        $value = do_shortcode('[audio src="' . $value . '"]');
                        break;
                    case 'video':
                        $value = do_shortcode('[video src="' . $value . '"]');
                        break;
                }
            }

            if (trim($value)) {
                $content .= '<div class="sb_mod_pods_single_item clearfix">' . $value . '</div>';
            } else {
                $content = '';
            }
        }

        //////////////////////////////////////////////////////////////////////

        if (trim($content) && trim($value)) {
            $output = sprintf(
                '<div%5$s class="%1$s%3$s%6$s">
												%2$s
										%4$s',
                'clearfix ',
                $content,
                esc_attr('et_pb_module'),
                '</div>',
                ('' !== $module_id ? sprintf(' id="%1$s"', esc_attr($module_id)) : ''),
                ('' !== $module_class ? sprintf(' %1$s', esc_attr($module_class)) : '')
            );
        }

        return $output;
    }
}

new et_pb_pods_single;

?>