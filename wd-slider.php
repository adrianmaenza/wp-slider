<?php

/**
 * Plugin Name:     WD Slider
 * Plugin URI:      webdev.co.zw
 * Description:     Installable Slick slider implementation
 * Version:         0.1  
 * Author:          Webdev
 * Author URI:      webdev.co.zw
 * Text Domain:     webdev
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly

function wd_slider_install()
{
    // Check if advanced custom fields is installed.
    wd_slider_check_acf();

    // Trigger our function that registers the custom post type
    wd_slider_setup_post_type();

    // Clear the permalinks after the post type has been registered
    flush_rewrite_rules();
}

function wd_slider_admin_notice__acf_missing()
{
    $string  = '<div class="notice notice-error is-dismissible">';
    $string .= '<p>Advanced custom fields required WD Slider to run correctly</p>';
    $string .= '</div>';

    echo $string;
}

function wd_slider_setup_post_type()
{
    $args = [
        'label'     => 'Slides',
        'public'    => true,
        'support'   => ['title', 'custom-fields']
    ];

    register_post_type('wd_slides', $args);

    remove_post_type_support( 'wd_slides', 'editor' );
}

function wd_slider_check_acf()
{
    if (!class_exists('ACF')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        deactivate_plugins(plugin_basename(__FILE__));
        return;
    }
}

function wd_slider_setup_custom_fields()
{
    if (function_exists('acf_add_local_field_group')) :

        $image_field = array(
            'key' => 'wd_slider_image',
            'label' => 'Slide image',
            'name' => 'wd_slide_image',
            'type' => 'image',
            'prefix' => 'wd',
            'instructions' => 'Add a slide image',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
            'readonly' => 0,
            'disabled' => 0,

            /* (string) Specify the type of value returned by get_field(). Defaults to 'array'.
            Choices of 'array' (Image Array), 'url' (Image URL) or 'id' (Image ID) */
            'return_format' => 'url',

            /* (string) Specify the image size shown when editing. Defaults to 'thumbnail'. */
            'preview_size' => 'thumbnail',

            /* (string) Restrict the image library. Defaults to 'all'.
            Choices of 'all' (All Images) or 'uploadedTo' (Uploaded to post) */
            'library' => 'all',

            /* (int) Specify the minimum width in px required when uploading. Defaults to 0 */
            'min_width' => 0,

            /* (int) Specify the minimum height in px required when uploading. Defaults to 0 */
            'min_height' => 0,

            /* (int) Specify the minimum filesize in MB required when uploading. Defaults to 0 
            The unit may also be included. eg. '256KB' */
            'min_size' => 0.05,

            /* (int) Specify the maximum width in px allowed when uploading. Defaults to 0 */
            'max_width' => 0,

            /* (int) Specify the maximum height in px allowed when uploading. Defaults to 0 */
            'max_height' => 0,

            /* (int) Specify the maximum filesize in MB in px allowed when uploading. Defaults to 0
            The unit may also be included. eg. '256KB' */
            'max_size' => 0.5,

            /* (string) Comma separated list of file type extensions allowed when uploading. Defaults to '' */
            'mime_types' => '',

        );

        acf_add_local_field_group(array(
            'key' => 'wd_slider_fields',
            'title' => 'WD Slider Custom Fields',
            'fields' => array(
                $image_field,
                array(
                    'key' => 'wd_slide_subtitle',
                    'label' => 'Sub Title',
                    'name' => 'wd_sub_title',
                    'type' => 'text',
                ),
                array(
                    'key' => 'wd_slide_cta_text',
                    'label' => 'CTA Text',
                    'name' => 'wd_cta_text',
                    'type' => 'text',
                ),
                array(
                    'key' => 'wd_slide_cta_link',
                    'label' => 'CTA link',
                    'name' => 'wd_cta_link',
                    'type' => 'url',
                    'instructions' => 'Enter the link where this slide will direct visitor',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'wd_slides',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
        ));

    endif;
}

function wd_slider_shortcode(){
    return 'This will display slides';
}

function wd_slides_css(){

}

function wd_slides_js(){
    
}

add_shortcode('wd_slides', 'wd_slider_shortcode');
add_action('init', 'wd_slider_setup_post_type');
add_action('init', 'wd_slider_check_acf');
add_action('acf/init', 'wd_slider_setup_custom_fields');

register_activation_hook(__FILE__, 'wd_slider_install');

