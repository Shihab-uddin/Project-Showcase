<?php
function sp_portfolio_shortcode( $atts ) {
    ob_start();

    // Get plugin options
    $settings = get_option( 'sp_portfolio_settings', [] );
    $title_bg         = $settings['title_bg_color'] ?? '#000';
    $title_color      = $settings['title_text_color'] ?? '#fff';
    $btn_bg           = $settings['preview_btn_bg_color'] ?? '#0073aa';
    $btn_color        = $settings['preview_btn_text_color'] ?? '#fff';
    $snippet_bg       = $settings['snippet_btn_bg_color'] ?? '#333';
    $snippet_color    = $settings['snippet_btn_text_color'] ?? '#fff';
    $layout_col       = (int) ($settings['layout_columns'] ?? 3);
    $display_mode     = $settings['display_mode'] ?? 'preview';
    $title_font_size  = $settings['title_font_size'] ?? 24;
    $button_font_size = $settings['button_font_size'] ?? 16;

    // CSS Variables
    $css_vars = "
        --sp-title-bg: {$title_bg};
        --sp-title-color: {$title_color};
        --sp-btn-bg: {$btn_bg};
        --sp-btn-color: {$btn_color};
        --sp-snippet-bg: {$snippet_bg};
        --sp-snippet-color: {$snippet_color};
        --sp-title-font-size: {$title_font_size}px;
        --sp-btn-font-size: {$button_font_size}px;
    ";

    // Shortcode attributes
    $atts = shortcode_atts([
        'category' => '',
    ], $atts);

    // WP_Query args
    $args = [
        'post_type'      => 'sp_portfolio',
        'posts_per_page' => -1,
    ];

    if ( !empty($atts['category']) ) {
        $args['tax_query'] = [[
            'taxonomy' => 'sp_portfolio_category',
            'field'    => 'slug',
            'terms'    => $atts['category'],
        ]];
    }

    $query = new WP_Query($args);

    // Output
    if ( $query->have_posts() ) {
        echo '<div class="sp-portfolio-grid" style="' . esc_attr($css_vars) . '">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $url       = get_post_meta( get_the_ID(), '_sp_project_url', true );
            $thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            ?>
            <div class="sp-portfolio-item" style="background-image: url('<?php echo esc_url($thumbnail); ?>');">
                <div class="sp-portfolio-title"><?php the_title(); ?></div>
                <div class="sp-portfolio-overlay">
                    <?php if ( $display_mode === 'preview' ): ?>
                        <a href="<?php echo esc_url( home_url( '/portfolio-preview/?project_id=' . get_the_ID() ) ); ?>" target="_blank" class="sp-portfolio-button">Live Preview</a>
                    <?php else: ?>
                        <a href="#" class="sp-portfolio-button sp-portfolio-snippet-button" data-img="<?php echo esc_url($thumbnail); ?>">View Snippet</a>
                    <?php endif; ?>
                </div>
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
add_shortcode( 'grvth_showcase', 'sp_portfolio_shortcode' );
