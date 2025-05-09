<?php
/**
 * Template for portfolio live preview
 */
if ( ! isset( $_GET['project_id'] ) ) {
    wp_redirect( home_url() );
    exit;
}

$project_id = absint( $_GET['project_id'] );
$project_url = get_post_meta( $project_id, '_sp_project_url', true );
$project_title = get_the_title( $project_id );

if ( ! $project_url ) {
    wp_redirect( home_url() );
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo esc_html( $project_title ); ?> - Live Preview</title>
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) . '../assets/sp-style.css'; ?>?v=<?php echo filemtime( plugin_dir_path( __FILE__ ) . '../assets/sp-style.css' ); ?>">

</head>
<body class="sp-preview">
    <div id="preview-bar">
        <div>You're viewing: <?php echo esc_html( $project_title ); ?></div>
        <div><a href="<?php echo esc_url( home_url( '/portfolio' ) ); ?>" class="back-link">← Back to Portfolio</a></div>
    </div>
    <iframe id="preview-frame" src="<?php echo esc_url( $project_url ); ?>"></iframe>
</body>
</html>

