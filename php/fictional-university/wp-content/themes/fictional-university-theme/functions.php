<?php
    
    function university_files() {
        wp_enqueue_style('google_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        if (strstr($_SERVER['SERVER_NAME'], "fictional-universities.local")) {
            wp_enqueue_script('main_university_js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
        } else {
            wp_enqueue_script('our_vendors_js', get_theme_file_uri('/bundled-assets/vendors~scripts.8c97d901916ad616a264.js'), NULL, '1.0', true);
            wp_enqueue_script('main_university_js', get_theme_file_uri('/bundled-assets/scripts.bc49dbb23afb98cfc0f7.js'), NULL, '1.0', true);
            wp_enqueue_style('our_main_styles', get_theme_file_uri('/bundled-assets/styles.bc49dbb23afb98cfc0f7.css'));
        }
        
        
        
    }

    // Call a function (second param) at a specified time (first param)
    add_action('wp_enqueue_scripts','university_files');

    function university_features() {
        // Register navigation slots for header and footer
        register_nav_menu('headerMenuLocation', 'Header Menu Location');
        register_nav_menu('footerLocationOne', 'Footer Location One');
        register_nav_menu('footerLocationTwo', 'Footer Location Two');
        add_theme_support('title-tag');
    }

    // Set a dynamic html title tag
    add_action('after_setup_theme', 'university_features');

?>