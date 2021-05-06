<?php
    
    function university_files() {
        // Get the style.css file
        wp_enqueue_style('university_main_styles', get_stylesheet_uri());
    }

    // Call a function (second param) at a specified time (first param)
    add_action('wp_enqueue_scripts','university_files');

?>