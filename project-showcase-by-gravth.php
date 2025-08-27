<?php
/*
Plugin Name: Project Showcase by Gravth
Description: Display your web projects with live previews, snippets pop up or via visiting the live site in a stylish grid.
Version: 1.0.0
Author: Shihab
Author URI: https://gravth.com/
License: GPLv2 or later
Text Domain: project-showcase-by-gravth
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Include required files
require_once plugin_dir_path( __FILE__ ) . 'includes/post-types.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';



function grvthps_enqueue_frontend_scripts() {
    if ( is_admin() ) {
        return;
    }

    global $post;
    if ( $post && has_shortcode( $post->post_content, 'grvthps_showcase' ) ) {
        wp_enqueue_script(
            'grvthps-popup-js',
            plugin_dir_url(__FILE__) . 'assets/js/sp-popup.js',
            array(),
            '1.0',
            true
        );

        $css_path = plugin_dir_path(__FILE__) . 'assets/sp-style.css';
        $css_url  = plugin_dir_url(__FILE__) . 'assets/sp-style.css';
        $ver      = file_exists( $css_path ) ? filemtime( $css_path ) : false;

        wp_enqueue_style(
            'grvthps-showcase-style',
            $css_url,
            array(),
            $ver
        );
    }
}
add_action( 'wp_enqueue_scripts', 'grvthps_enqueue_frontend_scripts' );




// Add rewrite rule
// Register rewrite endpoint for portfolio preview
function grvthps_register_preview_rewrite() {
    add_rewrite_rule(
        '^portfolio-preview/?',
        'index.php?grvthps_preview=1',
        'top'
    );
}
add_action('init', 'grvthps_register_preview_rewrite');

// Whitelist query vars
function grvthps_add_query_vars( $vars ) {
    $vars[] = 'grvthps_preview';
    $vars[] = 'grvthps_project_id';
    return $vars;
}
add_filter('query_vars', 'grvthps_add_query_vars');

// Load our custom template
function grvthps_preview_template( $template ) {
    if ( get_query_var('grvthps_preview') ) {
        return plugin_dir_path(__FILE__) . '/templates/portfolio-preview.php';
    }
    return $template;
}
add_filter('template_include', 'grvthps_preview_template');





add_action( 'wp_enqueue_scripts', 'grvthps_maybe_enqueue_preview_style' );
function grvthps_maybe_enqueue_preview_style() {
    if ( get_query_var( 'grvthps_portfolio_preview' ) ) {
        $path = plugin_dir_path( __FILE__ ) . 'assets/sp-style.css';
        $url  = plugin_dir_url( __FILE__ ) . 'assets/sp-style.css';
        $ver  = file_exists( $path ) ? filemtime( $path ) : false;
        wp_enqueue_style( 'grvthps-preview-style', $url, array(), $ver );
    }
}
