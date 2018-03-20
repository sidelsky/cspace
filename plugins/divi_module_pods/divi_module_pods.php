<?php

/*
 * Plugin Name: DIVI Pods Module
 * Plugin URI:  http://www.sean-barton.co.uk
 * Description: A plugin to add the ability to use Pods within the Divi builder
 * Author:      Sean Barton - Tortoise IT
 * Version:     1.2
 * Author URI:  http://www.sean-barton.co.uk
 *
 *
 * Changelog:
 *
 * V1.1
 * - Initial version
 * - Works in conjunction with Pods, a free and popular plugin for WordPress - https://en-gb.wordpress.org/plugins/pods/
 *
 * V1.2 - 16/02/2018
 * - fixed picking a single item in the relationship field or the image field as both were showing the wrong info.
 *
 */

//constants
define('SB_ET_PODS_VERSION', '1.2');
define('SB_ET_PODS_STORE_URL', 'https://elegantmarketplace.com');
define('SB_ET_PODS_ITEM_NAME', 'Pods Module for Divi');
define('SB_ET_PODS_AUTHOR_NAME', 'Sean Barton');
define('SB_ET_PODS_ITEM_ID', 436215);
define('SB_ET_PODS_FILE', __FILE__);

require_once('includes/emp-licensing.php');

add_action('plugins_loaded', 'sb_mod_pods_init');

function sb_mod_pods_init()
{
    add_action('et_builder_ready', 'sb_mod_pods_theme_setup', 9999);
    add_action('admin_head', 'sb_mod_pods_admin_head', 9999);
    add_action('wp_enqueue_scripts', 'sb_mod_pods_enqueue', 9999);
    add_action('admin_menu', 'sb_mod_pods_submenu');
}

function sb_mod_pods_submenu()
{
    add_submenu_page(
        'plugins.php',
        'Divi Pods Module',
        'Divi Pods Module',
        'manage_options',
        'sb_mod_pods',
        'sb_mod_pods_submenu_cb');
}

function sb_mod_pods_box_start($title)
{
    return '<div class="postbox">
                    <h2 class="hndle">' . $title . '</h2>
                    <div class="inside">';
}

function sb_mod_pods_box_end()
{
    return '    </div>
                </div>';
}

function sb_mod_pods_submenu_cb()
{

    echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
    echo '<h2>' . SB_ET_PODS_ITEM_NAME . ' - V' . SB_ET_PODS_VERSION . '</h2>';

    echo '<div id="poststuff">';

    echo '<div id="post-body" class="metabox-holder columns-2">';

    echo '<form method="POST">';

    sb_et_pods_license_page();

    echo '</form>';

    echo '</div>';
    echo '</div>';

    echo '</div>';
}

function sb_mod_pods_enqueue()
{
    wp_enqueue_style('sb_mod_pods_css', plugins_url('/style.css', __FILE__));
}

function sb_mod_pods_parse_value_by_type($field)
{

    $field = get_post($field);
    $type = get_post_meta($field->ID, 'type', true);
    $value = get_post_meta(get_the_ID(), $field->post_name, true);

    if (!$type) {
        return;
    }

    //echo $type;

    //echo '<pre>';
    //print_r($value);
    //echo '</pre>';

    if ($type == 'file') {
        $value_array = get_post_meta(get_the_ID(), '_pods_' . $field->post_name);
        $value_array = $value_array[0];
        $value = '';
        $gallery_ids = array();
        $file_wp_gallery_output = get_post_meta($field->ID, 'file_wp_gallery_output', true);
        $file_type = get_post_meta($field->ID, 'file_type', true); // = images

        if (count($value_array) > 1) {
            if (is_array($value_array)) {
                foreach ($value_array as $val) {
                    if ($file_wp_gallery_output) {
                        $gallery_ids[] = $val;

                    } else {
                        $val = get_post($val);
                        $val_content = sb_mod_pods_file_item($file_type, $val); //($val->post_title ? $val->post_title : $val->guid);
                        $value .= '<div class="sb-divi-pods-table-file-item-container"><a target="_blank" href="' . $val->guid . '" class="sb-divi-pods-table-file-item">' . $val_content . '</a></div>';
                    }
                }
            }
        } else if ($value_array = get_post($value_array[0])) {
            if ($file_wp_gallery_output) {
                $gallery_ids[] = $value_array->ID;

            } else {
                $val_content = sb_mod_pods_file_item($file_type, $value_array);
                $value .= '<a target="_blank" href="' . $value_array->guid . '" class="sb-divi-pods-table-file-item">' . $val_content . '</a>';
            }
        }

        if (count($gallery_ids) > 0 && $file_type == 'images') {
            $file_wp_gallery_link = get_post_meta($field->ID, 'file_wp_gallery_link', true);
            $file_wp_gallery_columns = get_post_meta($field->ID, 'file_wp_gallery_columns', true);
            $file_wp_gallery_random_sort = get_post_meta($field->ID, 'file_wp_gallery_random_sort', true);
            $file_wp_gallery_size = get_post_meta($field->ID, 'file_wp_gallery_size', true);

            $gallery_args = array(
                'ids' => $gallery_ids
            );

            if ($file_wp_gallery_random_sort) {
                $gallery_args['order'] = 'rand';
            }
            if ($file_wp_gallery_columns) {
                $gallery_args['columns'] = $file_wp_gallery_columns;
            }
            if ($file_wp_gallery_size) {
                $gallery_args['size'] = $file_wp_gallery_size;
            }
            if ($file_wp_gallery_link) {
                $gallery_args['link'] = $file_wp_gallery_link;
            }

            $value = gallery_shortcode($gallery_args);
        }

        //else {
        //$value = '<a target="_blank" href="' . $value . '" class="sb-divi-pods-table-file-item">' . $value . '</a>';
        //}
        /*} else if (is_array($value) && $field['type'] == 'image') {
            $prepend = '';
            $append = '';

            if ($link_image == 'page' || $link_image == 'image') {
                $url = $value['sizes']['large'];

                if ($link_image == 'page') {
                    $url = get_permalink(get_the_ID());
                }

                $prepend = '<a href="' . $url . '" class="sb-divi-pods-table-image-item">';
                $append = '</a>';
            }

            $value = $prepend . '<img src="' . (@$value['sizes'][$image_size] ? $value['sizes'][$image_size] : $value['sizes']['medium']) . '" />' . $append;
        } else if (is_array($value) && $field['type'] == 'gallery') {
            $value_cache = $value;
            $value = '';

            $value .= '<div class="et_pb_gallery_grid" style="display: block;">';
            $value .= '<div class="et_pb_gallery_items et_post_gallery">';

            foreach ($value_cache as $val) {
                $value .= '<div class="et_pb_gallery_item et_pb_grid_item et_pb_bg_layout_light" style="display: block;">';
                $value .= '<div class="et_pb_gallery_image landscape">';
                $value .= '<a href="' . $val['sizes']['large'] . '">';
                $value .= '<img src="' . (@$val['sizes'][$image_size] ? $val['sizes'][$image_size] : $val['sizes']['large']) . '" data-lazy-loaded="true" style="display: inline;">';
                $value .= '<span class="et_overlay et_pb_inline_icon" data-icon="T"></span>';
                $value .= '</a>';
                $value .= '</div>';
                $value .= '</div>';
            }

            $value .= '</div>';
            $value .= '</div>';

        */
    } else if ($type == 'boolean') {
        $value = get_post_meta($field->ID, 'boolean_' . ($value ? 'yes' : 'no') . '_label', true);

    } else if ($type == 'datetime') {
        //nothing needing to be done here

    } else if ($type == 'pick') {
        $value_array = get_post_meta(get_the_ID(), '_pods_' . $field->post_name);
        $value_array = $value_array[0];
        $value = '';

        //echo '<pre>';
        //print_r($value_array);
        //echo '</pre>';

        if (count($value_array) > 1) {
            if (is_array($value_array)) {
                $value .= '<ul class="sb-pods-relationships">';
                foreach ($value_array as $val) {
                    $val = get_post($val);
                    $val_content = ($val->post_title ? $val->post_title : $val->guid);; //sb_mod_pods_file_item($file_type, $val); //($val->post_title ? $val->post_title : $val->guid);
                    $value .= '<li class="sb-divi-pods-table-file-item-container"><a target="_blank" href="' . get_permalink($val->ID) . '" class="sb-divi-pods-table-file-item">' . $val_content . '</a></li>';
                }
                $value .= '</ul>';
            }
        } else if ($value_array = get_post($value_array[0])) {
            $val_content = ($value_array->post_title ? $value_array->post_title : $value_array->guid);; //sb_mod_pods_file_item($file_type, $value_array);
            $value .= '<p><a target="_blank" href="' . get_permalink($value_array->ID) . '" class="sb-divi-pods-table-file-item">' . $val_content . '</a></p>';
        }

        /*
        $value = '';
        if (!empty($field['value'])) {
            foreach ($field['value'] as $val) {
                if ($post = get_post($val)) {
                    $value .= '<li><a href="' . get_post_permalink($val) . '" target="_blank">' . apply_filters('the_title', $post->post_title) . '</a></li>';
                }
            }

            if ($value) {
                $value = '<ul class="sb-pods-field-checkboxes">' . $value . '</ul>';
            }
        }
    */
    }

    if (!is_array($value)) {
        $value = apply_filters('sb_et_mod_pods_field_fallback', $value, $field);
        $value = apply_filters('sb_et_mod_pods_field_fallback_' . $field->post_name, $value, $field);
    }

    $value = apply_filters('sb_et_mod_pods_field_parse', $value, $field);

    return $value;
}

function sb_mod_pods_file_item($file_type, $value_array)
{
    $content = ($value_array->post_title ? $value_array->post_title : $value_array->guid);

    if ($file_type == 'images') {
        if ($url = wp_get_attachment_image_src($value_array->ID, 'large')) {
            $content = '<img src="' . $url[0] . '" alt="' . $value_array->post_title . '" />';
        }
    }

    return $content;
}

function sb_mod_pods_admin_head()
{

    if (isset($_GET['post']) || isset($_GET['post_type']) || isset($_GET['sb_purge_cache'])) {
        $prop_to_remove = array(
            'et_pb_templates_et_pb_pods_single_item'
        , 'et_pb_templates_et_pb_pods_table_item'
        , 'et_pb_templates_et_pb_pods_table_items'
        );

        $js_prop_to_remove = 'var sb_ls_remove = ["' . implode('","', $prop_to_remove) . '"];';

        echo '<script>
	    
	    ' . $js_prop_to_remove . '
	    
	    for (var prop in localStorage) {
            if (sb_ls_remove.indexOf(prop) != -1) {
                localStorage.removeItem(prop);
            }
	    }
	    
	    </script>';
    }
}

function sb_mod_pods_theme_setup()
{

    if (class_exists('ET_Builder_Module')) {
        require_once('modules/et_pb_pods_table.php');
        require_once('modules/et_pb_pods_table_item.php');
        require_once('modules/et_pb_pods_single.php');
    }
}

function sb_mod_pods_get_fields()
{
    $options = array();

    if ($pods = get_posts(array('post_type' => '_pods_pod', 'posts_per_page' => -1))) {
        foreach ($pods as $pods_fg) {

            if ($fields = get_posts(array('post_type' => '_pods_field', 'post_parent' => $pods_fg->ID, 'posts_per_page' => -1))) {
                foreach ($fields as $field) {
                    $options[$pods_fg->post_title . '|' . $field->ID] = $pods_fg->post_title . ' - ' . $field->post_title;
                }
            }
        }
    }

    return $options;
}

?>