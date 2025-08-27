<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Add Meta Boxes
function grvthps_add_portfolio_meta_boxes() {
    add_meta_box(
        'grvthps_portfolio_details',
        'Portfolio Details',
        'grvthps_render_portfolio_meta_box',
        'grvthps_portfolio',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'grvthps_add_portfolio_meta_boxes' );

function grvthps_render_portfolio_meta_box( $post ) {
    wp_nonce_field( 'grvthps_portfolio_meta', 'grvthps_portfolio_meta_nonce' );

    $project_type = get_post_meta( $post->ID, '_grvthps_project_type', true );
    $project_url = get_post_meta( $post->ID, '_grvthps_project_url', true );
    ?>
    <p>
        <label for='grvthps_project_type'>Project Type:</label>
        <select name='grvthps_project_type' id='grvthps_project_type'>
            <option value="live" <?php selected( $project_type, 'live' ); ?>>Live</option>
            <option value="demo" <?php selected( $project_type, 'demo' ); ?>>Demo</option>
        </select>
    </p>
    <p>
        <label for='grvthps_project_url'>Project URL:</label>
        <input type="url" name='grvthps_project_url' id='grvthps_project_url' value="<?php echo esc_attr( $project_url ); ?>" style="width:100%;" />
    </p>
    <?php
}




function grvthps_save_portfolio_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! isset( $_POST['grvthps_portfolio_meta_nonce'] ) || ! wp_verify_nonce( $_POST['grvthps_portfolio_meta_nonce'], 'grvthps_portfolio_meta' ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['grvthps_project_type'] ) ) {
        update_post_meta( $post_id, '_grvthps_project_type', sanitize_text_field( wp_unslash( $_POST['grvthps_project_type'] ) ) );
    } elseif ( isset( $_POST['sp_project_type'] ) ) {
        update_post_meta( $post_id, '_grvthps_project_type', sanitize_text_field( wp_unslash( $_POST['sp_project_type'] ) ) );
    }

    if ( isset( $_POST['grvthps_project_url'] ) ) {
        update_post_meta( $post_id, '_grvthps_project_url', esc_url_raw( wp_unslash( $_POST['grvthps_project_url'] ) ) );
    } elseif ( isset( $_POST['sp_project_url'] ) ) {
        update_post_meta( $post_id, '_grvthps_project_url', esc_url_raw( wp_unslash( $_POST['sp_project_url'] ) ) );
    }
}
add_action( 'save_post', 'grvthps_save_portfolio_meta' );
