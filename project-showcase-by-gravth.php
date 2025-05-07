<?php
/**
 * Plugin Name: Project Showcase by Gravth
 * Description: A simple portfolio plugin to showcase your projects (live and demo).
 * Version: 1.0.0
 * Author: Gravth
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Include required files
require_once plugin_dir_path( __FILE__ ) . 'includes/post-types.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';



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
        include plugin_dir_path( __FILE__ ) . 'portfolio-preview.php';
        exit;
    }
}
add_action( 'template_redirect', 'sp_template_redirect' );

