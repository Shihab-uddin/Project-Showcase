<?php
function sp_portfolio_shortcode( $atts ) {
    ob_start();

    // Get plugin options
    $settings = get_option( 'sp_portfolio_settings', [] );
    $title_bg    = $settings['title_bg_color'] ?? '#000';
    $title_color = $settings['title_text_color'] ?? '#fff';
    $btn_bg      = $settings['preview_btn_bg_color'] ?? '#0073aa';
    $btn_color   = $settings['preview_btn_text_color'] ?? '#fff';
    $snippet_bg  = $settings['snippet_btn_bg_color'] ?? '#333';
    $snippet_color = $settings['snippet_btn_text_color'] ?? '#fff';
    $layout_col  = (int) ($settings['layout_columns'] ?? 3);
    $display_mode = $settings['display_mode'] ?? 'preview';
    $settings = get_option( 'sp_portfolio_settings', [] );
    $title_font_size = isset($settings['title_font_size']) ? $settings['title_font_size'] : 24;
    $button_font_size = isset($settings['button_font_size']) ? $settings['button_font_size'] : 16;

    ?>
    <style>
        .sp-portfolio-grid {
            display: grid;
            grid-template-columns: repeat(<?php echo $layout_col; ?>, 1fr);
            gap: 20px;
        }

        .sp-portfolio-item {
            position: relative;
            overflow: hidden;
            aspect-ratio: 4 / 5;
            width: 100%;
            height: 400px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top center;
            transition: background-position 5s linear;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .sp-portfolio-item:hover {
            background-position: bottom center;
        }

        .sp-portfolio-title {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: <?php echo esc_attr($title_bg); ?>;
            color: <?php echo esc_attr($title_color); ?>;
            text-align: center;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        .sp-portfolio-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            opacity: 0; /* 🔑 This hides it initially */
            transition: opacity 0.3s ease;
        }

        .sp-portfolio-button {
            display: inline-block;
            margin: 5px;
            padding: 8px 15px;
            background: <?php echo esc_attr($btn_bg); ?>;
            color: <?php echo esc_attr($btn_color); ?>;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .sp-portfolio-item:hover .sp-portfolio-overlay {
            opacity: 1;
        }

        .sp-portfolio-snippet-button {
            background: <?php echo esc_attr($snippet_bg); ?>;
            color: <?php echo esc_attr($snippet_color); ?>;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.sp-portfolio-snippet-button').forEach(btn => {
                btn.addEventListener('click', e => {
                    e.preventDefault();
                    const imgUrl = btn.dataset.img;
                    const popup = document.createElement('div');
                    popup.style.position = 'fixed';
                    popup.style.top = 0;
                    popup.style.left = 0;
                    popup.style.width = '100%';
                    popup.style.height = '100%';
                    popup.style.background = 'rgba(0,0,0,0.8)';
                    popup.style.display = 'flex';
                    popup.style.alignItems = 'center';
                    popup.style.justifyContent = 'center';
                    popup.style.zIndex = 10000;
                    popup.innerHTML = '<img src="' + imgUrl + '" style="max-width:90%; max-height:90%; border-radius:10px;" />';
                    popup.addEventListener('click', () => popup.remove());
                    document.body.appendChild(popup);
                });
            });
        });
    </script>
    <?php

    $atts = shortcode_atts(array(
        'category' => '', // slug of the category
    ), $atts);

    $args = array(
        'post_type' => 'sp_portfolio',
        'posts_per_page' => -1,
    );

    if (!empty($atts['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'sp_portfolio_category',
                'field'    => 'slug',
                'terms'    => $atts['category'],
            ),
        );
    }

    $query = new WP_Query($args);
        
    ob_start();

    if ($query->have_posts()) {
        echo '<div class="sp-portfolio-grid">';
        while ($query->have_posts()) {
            $query->the_post();
            // Your existing markup here
        }
        echo '</div>';
    }
    

    if ( $query->have_posts() ) {
        echo '<div class="sp-portfolio-grid">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $url = get_post_meta( get_the_ID(), '_sp_project_url', true );
            $thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            ?>
            <div class="sp-portfolio-item" style="background-image: url('<?php echo esc_url( $thumbnail ); ?>');">
                <div class="sp-portfolio-title" style="font-size: <?php echo esc_attr($title_font_size); ?>px;"><?php the_title(); ?></div>
                <div class="sp-portfolio-overlay">
                    <?php if ( $display_mode === 'preview' ): ?>
                        <a href="<?php echo esc_url( home_url( '/portfolio-preview/?project_id=' . get_the_ID() ) ); ?>" target="_blank" class="sp-portfolio-button" style="font-size: <?php echo esc_attr($button_font_size); ?>px;">Live Preview</a>
                    <?php else: ?>
                        <a href="#" class="sp-portfolio-button sp-portfolio-snippet-button" data-img="<?php echo esc_url( $thumbnail ); ?>" style="font-size: <?php echo esc_attr($button_font_size); ?>px;">View Snippet</a>
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