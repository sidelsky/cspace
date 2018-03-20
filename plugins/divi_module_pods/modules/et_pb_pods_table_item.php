<?php

class et_pb_pods_table_item extends ET_Builder_Module
{
    function init()
    {
        $this->name = esc_html__('Pods Field', 'et_builder');
        $this->slug = 'et_pb_pods_table_item';
        $this->type = 'child';
        $this->child_title_var = 'title';

        $this->whitelisted_fields = array(
            'title',
            'field_name',
            'module_id',
            'module_class',
        );

        $this->options_toggles = array(
            'general' => array(
                'toggles' => array(
                    'main_settings' => esc_html__('Main Settings', 'et_builder'),
                ),
            ),
        );

        $this->advanced_setting_title_text = esc_html__('New Pods Field', 'et_builder');
        $this->settings_text = esc_html__('Pods Field Settings', 'et_builder');
        $this->main_css_element = '%%order_class%%';

        $this->advanced_options = array(
            'fonts' => array(
                'text' => array(
                    'label' => esc_html__('Label', 'et_builder'),
                    'css' => array(
                        'main' => "td{$this->main_css_element}.sb_mod_pods_table_item.sb_mod_pods_table_item_label",
                    ),
                    'font_size' => array('default' => '14px'),
                    'line_height' => array('default' => '1.5em'),
                ),
                'value' => array(
                    'label' => esc_html__('Value', 'et_builder'),
                    'css' => array(
                        'main' => "td{$this->main_css_element}.sb_mod_pods_table_item.sb_mod_pods_table_item_value",
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
        $options = sb_mod_pods_get_fields();

        $fields = array(
            'field_name' => array(
                'label' => __('Field', 'et_builder'),
                'type' => 'select',
                'options' => $options,
                'description' => __('Pick which field to show.', 'et_builder'),
                'toggle_slug' => 'main_settings',
            ),
            'title' => array(
                'label' => esc_html__('Title', 'et_builder'),
                'type' => 'text',
                'description' => esc_html__('The label will be used for this field on the front end.', 'et_builder'),
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

        global $et_pt_pods_table_titles;
        global $et_pt_pods_table_classes;

        $title = $this->shortcode_atts['title'];
        $field = $this->shortcode_atts['field_name'];

        $module_class = ET_Builder_Element::add_module_order_class('', $function_name);

        $et_pt_pods_table_titles[] = '' !== $title ? $title : esc_html__('PODS Field', 'et_builder');
        $et_pt_pods_table_classes[] = $module_class;

        $output = '';

        if ($field_arr = explode('|', $field)) {
            $field = $field_arr[1];
        }

        $pods_post = get_post($field);

        //if (get_post_meta(get_the_ID(), $pods_post->post_name, true)) {

        if (!$title) {
            $title = $pods_post->post_title;
        }

        $value = sb_mod_pods_parse_value_by_type($field);

        if ($value) {
            $output = '<tr>
                                <td valign="top" class="sb_mod_pods_table_item sb_mod_pods_table_item_label clearfix ' . esc_attr($module_class) . '">
                                ' . $title . '
                                </td>
                                <td valign="top" class="sb_mod_pods_table_item sb_mod_pods_table_item_value ' . esc_attr($module_class) . '">' . $value . '</td>
                           </tr>';
        }
        //}

        return $output;
    }
}

new et_pb_pods_table_item;

?>