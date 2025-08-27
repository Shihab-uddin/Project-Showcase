<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Register Portfolio Custom Post Type
function grvthps_register_portfolio_post_type() {
    $labels = array(
        'name' => 'Project Showcase',
        'singular_name' => 'Project',
        'add_new' => 'Add New Project',
        'add_new_item' => 'Add New Project Showcase',
        'edit_item' => 'Edit Project',
        'new_item' => 'New Project',
        'view_item' => 'View Project',
        'all_items' => 'All Projects',
        'search_items' => 'Search Projects',
        'not_found' => 'No Project found.',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'supports' => array( 'title', 'editor', 'thumbnail' ),
        'menu_icon' => 'dashicons-portfolio',
        'has_archive' => true,
        'rewrite' => array( 'slug' => 'portfolio' ),
    );

    register_post_type( 'grvthps_portfolio', $args );
}
add_action( 'init', 'grvthps_register_portfolio_post_type' );
