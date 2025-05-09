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



function sp_enqueue_frontend_scripts() {
    if ( ! is_admin() && is_singular() && has_shortcode(get_post()->post_content, 'grvth_showcase') ) {
        wp_enqueue_script(
            'sp-popup-js',
            plugin_dir_url(__FILE__) . 'assets/js/sp-popup.js',
            array(),
            '1.0',
            true
        );
        wp_enqueue_style(
            'sp-showcase-style',
            plugin_dir_url(__FILE__) . 'assets/sp-style.css',
            [],
            filemtime(plugin_dir_path(__FILE__) . 'assets/sp-style.css')
        );
    }
}
add_action( 'wp_enqueue_scripts', 'sp_enqueue_frontend_scripts' );



// Add rewrite rule
function sp_add_preview_rewrite() {
    add_rewrite_rule(
        '^portfolio-preview/?$',
        'index.php?sp_portfolio_preview=1',
        'top'
    );
}
add_action( 'init', 'sp_add_preview_rewrite' );

function sp_add_query_var( $vars ) {
    $vars[] = 'sp_portfolio_preview';
    return $vars;
}
add_filter( 'query_vars', 'sp_add_query_var' );

function sp_template_redirect() {
    if ( get_query_var( 'sp_portfolio_preview' ) ) {
        include plugin_dir_path( __FILE__ ) . 'templates/portfolio-preview.php';
        exit;
    }
}
add_action( 'template_redirect', 'sp_template_redirect' );

