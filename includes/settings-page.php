<?php
// Add Settings Page to the Project Showcase Menu
function sp_register_project_showcase_settings_page() {
    // Ensure "edit.php?post_type=sp_portfolio" is the menu slug for your custom post type
    add_submenu_page(
        'edit.php?post_type=sp_portfolio', // Parent slug (custom post type)
        'Project Showcase Settings', // Page Title
        'Settings', // Menu Title
        'manage_options', // Required capability
        'sp_portfolio_settings', // Menu slug
        'sp_render_settings_page' // Callback function to render the settings page
    );
}
add_action( 'admin_menu', 'sp_register_project_showcase_settings_page' );

function sp_register_project_taxonomy() {
    register_taxonomy(
        'sp_portfolio_category',
        'sp_portfolio',
        array(
            'label' => 'Project Categories',
            'hierarchical' => true, // Like default categories (set to false for tags)
            'public' => true,
            'show_ui' => true,
            'show_in_rest' => true, // For block editor support
            'rewrite' => array('slug' => 'project-category'),
        )
    );
}
add_action('init', 'sp_register_project_taxonomy');


// Register Settings
function sp_register_settings() {
    register_setting( 'sp_portfolio_settings_group', 'sp_portfolio_settings' );
}
add_action( 'admin_init', 'sp_register_settings' );

// Render Settings Page
function sp_render_settings_page() {
    $settings = get_option( 'sp_portfolio_settings', [] );
    ?>
    <div class="wrap">
        <h1>Project Showcase Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'sp_portfolio_settings_group' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="title_bg_color">Shortcode: </label></th>
                    <td>[grvth_showcase] | Category flter: [grvth_showcase category="your-category"]</td>
                </tr>
                <tr>
                    <th scope="row"><label for="title_bg_color">Title Background Color</label></th>
                    <td><input type="color" name="sp_portfolio_settings[title_bg_color]" value="<?php echo esc_attr($settings['title_bg_color'] ?? '#000000'); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="title_text_color">Title Text Color</label></th>
                    <td><input type="color" name="sp_portfolio_settings[title_text_color]" value="<?php echo esc_attr($settings['title_text_color'] ?? '#ffffff'); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="preview_btn_bg_color">Preview Button Background</label></th>
                    <td><input type="color" name="sp_portfolio_settings[preview_btn_bg_color]" value="<?php echo esc_attr($settings['preview_btn_bg_color'] ?? '#0073aa'); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="preview_btn_text_color">Preview Button Text Color</label></th>
                    <td><input type="color" name="sp_portfolio_settings[preview_btn_text_color]" value="<?php echo esc_attr($settings['preview_btn_text_color'] ?? '#ffffff'); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="snippet_btn_bg_color">Snippet Button Background</label></th>
                    <td><input type="color" name="sp_portfolio_settings[snippet_btn_bg_color]" value="<?php echo esc_attr($settings['snippet_btn_bg_color'] ?? '#333333'); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="snippet_btn_text_color">Snippet Button Text Color</label></th>
                    <td><input type="color" name="sp_portfolio_settings[snippet_btn_text_color]" value="<?php echo esc_attr($settings['snippet_btn_text_color'] ?? '#ffffff'); ?>"></td>
                </tr>
                <tr>
                <th scope="row"><label for="title_font_size">Title Font Size</label></th>
                    <td>
                        <input type="number" name="sp_portfolio_settings[title_font_size]" value="<?php echo esc_attr($settings['title_font_size'] ?? 24); ?>" min="10" max="100"> px
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="button_font_size">Button Font Size</label></th>
                    <td>
                        <input type="number" name="sp_portfolio_settings[button_font_size]" value="<?php echo esc_attr($settings['button_font_size'] ?? 16); ?>" min="10" max="40"> px
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="layout_columns">Layout Columns</label></th>
                    <td>
                        <select name="sp_portfolio_settings[layout_columns]">
                            <?php for ( $i = 1; $i <= 6; $i++ ) : ?>
                                <option value="<?php echo $i; ?>" <?php selected($settings['layout_columns'] ?? 3, $i); ?>><?php echo $i; ?> Column<?php echo $i > 1 ? 's' : ''; ?></option>
                            <?php endfor; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="display_mode">Display Mode</label></th>
                    <td>
                        <select name="sp_portfolio_settings[display_mode]">
                            <option value="preview" <?php selected($settings['display_mode'] ?? '', 'preview'); ?>>Live Preview</option>
                            <option value="snippet" <?php selected($settings['display_mode'] ?? '', 'snippet'); ?>>Image Snippet</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
