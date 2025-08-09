<?php
/*
Plugin Name: Typewriting Author ARC Plugin
Description: Displays a MailerLite form for ARC signups based on selected product author.
Author: Byron Iniotakis
Version: 1.0.001
*/

// Admin Settings
add_action('admin_menu', function() {
    add_options_page('Author ARC Form Settings', 'Author ARC Forms', 'manage_options', 'author-arc-settings', 'arc_settings_page');
});

function arc_settings_page() {
    if (isset($_POST['arc_save'])) {
        $raw_forms = [];
        if (isset($_POST['author_forms'])) {
            foreach ($_POST['author_forms'] as $slug => $html) {
                $raw_forms[$slug] = wp_unslash($html);
            }
        }
        update_option('arc_author_forms', wp_json_encode($raw_forms));
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $author_forms = json_decode(get_option('arc_author_forms', '{}'), true);
    $all_authors = get_terms(['taxonomy' => 'product_author', 'hide_empty' => false]);

    echo '<div class="wrap"><h1>Author ARC Forms</h1>';
    echo '<form method="post">';
    foreach ($all_authors as $author_term) {
        $slug = $author_term->slug;
        $form_html = isset($author_forms[$slug]) ? $author_forms[$slug] : '';
        echo "<div><label><strong>{$author_term->name}</strong></label><br>";
        echo "<textarea name='author_forms[$slug]' rows='5' cols='70' placeholder='Paste MailerLite HTML here'>" . esc_textarea($form_html) . "</textarea></div><br>";
    }
    echo '<input type="submit" name="arc_save" class="button-primary" value="Save Settings">';
    echo '</form></div>';
}

// Shortcode Output
function arc_dropdown_shortcode() {
    $author_forms = json_decode(get_option('arc_author_forms', '{}'), true);
    $all_authors = get_terms(['taxonomy' => 'product_author', 'hide_empty' => false]);

    ob_start();
    echo '<select id="arcSelect" onchange="arcShowForm()">';
    echo '<option value="">-- Select an author --</option>';
    foreach ($all_authors as $term) {
        echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
    }
    echo '</select>';

    echo '<div id="arcFormContainer">';
    foreach ($author_forms as $slug => $form_html) {
        echo '<div id="arc-form-' . esc_attr($slug) . '" class="arc-form-box" style="display:none;">';
        echo $form_html;
        echo '</div>';
    }
    echo '</div>';

    return ob_get_clean();
}
add_shortcode('author_arc_form', 'arc_dropdown_shortcode');

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'author-arc-handler',
        plugin_dir_url(__FILE__) . 'assets/js/form-handler.js',
        [],
        '1.0.0',
        true
    );
});
