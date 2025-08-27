<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Template for portfolio live preview
 */

// Prefer WP's query var first (registered in your main plugin), fall back to $_GET if needed.
$project_id = get_query_var( 'grvthps_project_id' );
$project_id = $project_id ? absint( $project_id ) : ( isset( $_GET['grvthps_project_id'] ) ? absint( wp_unslash( $_GET['grvthps_project_id'] ) ) : 0 );

// Validate id
if ( ! $project_id ) {
    wp_redirect( esc_url_raw( home_url() ) );
    exit;
}

// Require a valid nonce for this specific project
$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
if ( ! wp_verify_nonce( $nonce, 'grvthps_view_portfolio_' . $project_id ) ) {
    wp_die( esc_html__( 'Invalid request. Please refresh and try again.', 'project-showcase-by-gravth' ) );
}

$project_url   = get_post_meta( $project_id, '_grvthps_project_url', true );
$project_title = get_the_title( $project_id );

if ( ! $project_url ) {
    wp_redirect( esc_url_raw( home_url() ) );
    exit;
}

// Enqueue preview stylesheet
$style_path = plugin_dir_path( __FILE__ ) . '../assets/sp-style.css';
$style_url  = plugin_dir_url( __FILE__ ) . '../assets/sp-style.css';
$ver        = file_exists( $style_path ) ? filemtime( $style_path ) : false;
wp_enqueue_style( 'grvthps-preview-style', $style_url, array(), $ver );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo esc_html( $project_title ); ?> - <?php echo esc_html__( 'Live Preview', 'project-showcase-by-gravth' ); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'sp-preview' ); ?>>
    <div id="preview-bar">
        <div><?php echo esc_html__( "You're viewing:", 'project-showcase-by-gravth' ); ?> <?php echo esc_html( $project_title ); ?></div>
        <div><a href="<?php echo esc_url( home_url( '/portfolio' ) ); ?>" class="back-link">← <?php echo esc_html__( 'Back to Portfolio', 'project-showcase-by-gravth' ); ?></a></div>
    </div>
    <iframe id="preview-frame" src="<?php echo esc_url( $project_url ); ?>"></iframe>
    <?php wp_footer(); ?>
</body>
</html>
