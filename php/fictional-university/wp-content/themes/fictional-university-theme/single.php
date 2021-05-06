<?php 
    // Find the header.php file and display
    get_header();

    // Get blog posts
    // Loop through all blog posts
    while(have_posts()) {
        // Get the current post in the while loop
        the_post();?>
        <!-- Get the title from the current post in the loop -->
        <h2><?php the_title(); ?></h2>
        <!-- Get the content from the current post in the loop -->
        <p><?php the_content(); ?></p>
    <?php }

?>

<!-- Find the footer.php file and display -->
<?php get_footer(); ?>