<?php
    
    function university_files() {
        wp_enqueue_style('google_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        // Get the style.css file
        wp_enqueue_style('university_main_styles', get_stylesheet_uri());

        wp_enqueue_script('main_university_js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
    }

    // Call a function (second param) at a specified time (first param)
    add_action('wp_enqueue_scripts','university_files');

?>