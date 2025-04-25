<?php
function sp_portfolio_shortcode( $atts ) {
    ob_start();

    $query = new WP_Query([
        'post_type' => 'sp_portfolio',
        'posts_per_page' => -1
    ]);

    if ( $query->have_posts() ) {
        echo '<div class="sp-portfolio-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:20px;">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $type = get_post_meta( get_the_ID(), '_sp_project_type', true );
            $url = get_post_meta( get_the_ID(), '_sp_project_url', true );
            ?>
            <div class="sp-portfolio-item" style="border:1px solid #ddd; padding:15px;">
                <?php the_post_thumbnail( 'medium' ); ?>
                <h3><?php the_title(); ?></h3>
                <p>Type: <?php echo esc_html( ucfirst( $type ) ); ?></p>
                <a href="<?php echo esc_url( home_url( '/portfolio-preview/?project_id=' . get_the_ID() ) ); ?>" target="_blank" style="display:inline-block;margin-top:10px;padding:8px 15px;background:#0073aa;color:#fff;text-decoration:none;border-radius:5px;">
                    Live Preview
                </a>

            </div>
            <?php
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p>No portfolio projects found.</p>';
    }

    return ob_get_clean();
}
add_shortcode( 'shihab_portfolio', 'sp_portfolio_shortcode' );
