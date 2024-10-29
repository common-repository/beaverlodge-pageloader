<?php
/*
Plugin Name: Beaverlodge Pageloader
Plugin URI: https://beaverlodgehq.com
Description: Add animated pageloader to your site
Version: 1.0.4
Author: Beaverlodge HQ
Author URI: https://beaverlodgehq.com
*/

function beaverlodge_pageloader_scripts() {
    wp_enqueue_script( 'beaverlodge-pageloader', plugin_dir_url( __FILE__ ) . 'beaverlodge-pageloader.js' );
    wp_enqueue_style( 'beaverlodge-pageloader', plugin_dir_url( __FILE__ ) . 'beaverlodge-pageloader.css' );
}
add_action( 'wp_enqueue_scripts', 'beaverlodge_pageloader_scripts' );

function beaverlodge_pageloader_styles() {
        $pageloader = get_theme_mod( 'pagloader-upload', plugins_url( 'images/pageloader.gif', __FILE__ ) );
        $pageloaderbackground = get_theme_mod( 'pageloader-color-setting', '#ffffff' );

        $custom_css = "
                .bl-pageloader {
                        background: url({$pageloader}) center no-repeat {$pageloaderbackground};
                }";
        wp_add_inline_style( 'beaverlodge-pageloader', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'beaverlodge_pageloader_styles' );

function beaverlodge_pageloader_customizer_scripts() {
        $pageloaderspeed = get_theme_mod( 'pageloader-speed', '400' );

        $custom_js = "
                jQuery(window).load(function() {
                    jQuery('.bl-pageloader').fadeOut({$pageloaderspeed});
                });";
        wp_add_inline_script( 'beaverlodge-pageloader', $custom_js );
}
add_action( 'wp_enqueue_scripts', 'beaverlodge_pageloader_customizer_scripts' );


function beaverlodge_pageloader_register( $wp_customize ) {
    $wp_customize->add_section(
        'bl_pageloader',
        array(
            'title' => 'Pageloader',
            'description' => 'Add your pageloader image.',
            'priority' => 35,
        )
    );
    $wp_customize->add_setting( 
        'pagloader-upload', array(
        'default' => plugins_url( 'images/pageloader.gif', __FILE__ ),
    ) );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'pagloader-upload',
            array(
                'label' => 'Image Upload',
                'section' => 'bl_pageloader',
                'settings' => 'pagloader-upload'
            )
        )
    );
    $wp_customize->add_setting( 
        'pageloader-color-setting', array(
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'pageloader-color-setting',
            array(
                'label' => 'Background Color',
                'section' => 'bl_pageloader',
                'settings' => 'pageloader-color-setting',
            )
        )
    );
    $wp_customize->add_setting(
        'pageloader-speed',
        array(
            'default' => '400',
        )
    );
    $wp_customize->add_control(
        'pageloader-speed',
        array(
            'label' => 'Fadeout Speed',
            'section' => 'bl_pageloader',
            'type' => 'text',
            'settings' => 'pageloader-speed',
			'description' => '(ms)',
        )
    );
}
add_action( 'customize_register', 'beaverlodge_pageloader_register' );



function beaverlodge_pageloader_svg($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'beaverlodge_pageloader_svg');
