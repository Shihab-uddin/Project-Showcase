<?php
// Add Meta Boxes
function sp_add_portfolio_meta_boxes() {
    add_meta_box(
        'sp_portfolio_details',
        'Portfolio Details',
        'sp_render_portfolio_meta_box',
        'sp_portfolio',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'sp_add_portfolio_meta_boxes' );

function sp_render_portfolio_meta_box( $post ) {
    $project_type = get_post_meta( $post->ID, '_sp_project_type', true );
    $project_url = get_post_meta( $post->ID, '_sp_project_url', true );
    ?>
    <p>
        <label for="sp_project_type">Project Type:</label>
        <select name="sp_project_type" id="sp_project_type">
            <option value="live" <?php selected( $project_type, 'live' ); ?>>Live</option>
            <option value="demo" <?php selected( $project_type, 'demo' ); ?>>Demo</option>
        </select>
    </p>
    <p>
        <label for="sp_project_url">Project URL:</label>
        <input type="url" name="sp_project_url" id="sp_project_url" value="<?php echo esc_attr( $project_url ); ?>" style="width:100%;" />
    </p>
    <?php
}

function sp_save_portfolio_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if ( isset( $_POST['sp_project_type'] ) ) {
        update_post_meta( $post_id, '_sp_project_type', sanitize_text_field( $_POST['sp_project_type'] ) );
    }

    if ( isset( $_POST['sp_project_url'] ) ) {
        update_post_meta( $post_id, '_sp_project_url', esc_url_raw( $_POST['sp_project_url'] ) );
    }
}
add_action( 'save_post', 'sp_save_portfolio_meta' );
