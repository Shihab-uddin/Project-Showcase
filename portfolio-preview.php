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
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        #preview-bar {
            background: #111;
            color: #fff;
            padding: 10px 20px;
            font-family: sans-serif;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #preview-frame {
            width: 100%;
            height: calc(100% - 50px);
            border: none;
        }
        a.back-link {
            color: #00baff;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="preview-bar">
        <div>You're viewing: <?php echo esc_html( $project_title ); ?></div>
        <div><a href="<?php echo esc_url( home_url( '/portfolio' ) ); ?>" class="back-link">← Back to Portfolio</a></div>
    </div>
    <iframe id="preview-frame" src="<?php echo esc_url( $project_url ); ?>"></iframe>
</body>
</html>
