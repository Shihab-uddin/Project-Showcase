<?php
function sp_portfolio_shortcode( $atts ) {
    ob_start();

    // Inline styles
    ?>
    <style>
        .sp-portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .sp-portfolio-item {
            position: relative;
            overflow: hidden;
            aspect-ratio: 4 / 5;
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

        .sp-portfolio-overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            color: #fff;
            transition: opacity 0.3s ease;
        }

        .sp-portfolio-button {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #0073aa;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .sp-portfolio-item:hover .sp-portfolio-button {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <?php

    $query = new WP_Query([
        'post_type' => 'sp_portfolio',
        'posts_per_page' => -1
    ]);

    if ( $query->have_posts() ) {
        echo '<div class="sp-portfolio-grid">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $type = get_post_meta( get_the_ID(), '_sp_project_type', true );
            $url = get_post_meta( get_the_ID(), '_sp_project_url', true );
            $thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            ?>
            <div class="sp-portfolio-item" style="background-image: url('<?php echo esc_url( $thumbnail ); ?>');">
                <div class="sp-portfolio-overlay">
                    <h3><?php the_title(); ?></h3>
                    <p>Type: <?php echo esc_html( ucfirst( $type ) ); ?></p>
                    <a href="<?php echo esc_url( home_url( '/portfolio-preview/?project_id=' . get_the_ID() ) ); ?>" target="_blank" class="sp-portfolio-button">
                        Live Preview
                    </a>
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
add_shortcode( 'shihab_portfolio', 'sp_portfolio_shortcode' );
