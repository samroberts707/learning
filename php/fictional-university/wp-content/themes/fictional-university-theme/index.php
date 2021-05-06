<?php
    // Find the header.php file and display
    get_header();

    // Get blog posts
    // Loop through all blog posts
    while(have_posts()) {
        // Get the current post in the while loop
        the_post();?>
        <!-- Get the link for the current post in the loop -- This includes the whole link including domain -->
        <a href="<?php the_permalink(); ?>">
            <!-- Get the title from the current post in the loop -->
            <h2><?php the_title(); ?></h2>
        </a>
        <!-- Get the content from the current post in the loop -->
        <p><?php the_content(); ?></p>
        <hr />
    <?php }

?>
<!-- Get Site Title from WP Admin -->
<h1><?php blogInfo('name'); ?></h1>

<!-- Get Site Tagline from WP Admin -->
<p><?php blogInfo('description'); ?></p>

<!-- Find the footer.php file and display -->
<?php get_footer(); ?>