<?php 
    // Find the header.php file and display
    get_header();

    // Get blog posts
    // Loop through all blog posts
    while(have_posts()) {
        // Get the current post in the while loop
        the_post();
    }
?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg'); ?>);"></div>
  <div class="page-banner__content container container--narrow">
    <h1 class="page-banner__title"><?php the_title(); ?></h1>
    <div class="page-banner__intro">
      <p>DONT FORGET TO REPLACE ME LATER!</p>
    </div>
  </div>
</div>

  <div class="container container--narrow page-section">
    
    <?php 
        // Get the parent ID, if none it will return 0
        $theParent = wp_get_post_parent_id(get_the_ID());
        if ($theParent) {
    ?>
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>
    <?php } ?>

    <?php 
    // Check to see if the current page is a parent page
    $isParent = get_pages(array(
      'child_of' => get_the_ID()
    ));
    
    // So long as the current page is either a child or a parent
    // We don't want to display sub-nav if the page is a single level
    if ($theParent or $isParent) { 
      
    ?>

    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
      <ul class="min-list">
        <?php 
          if ($theParent) {
            // If this is a child page set parent ID
            $findChildrenOf = $theParent;
          } else {
            // else set the current page ID
            $findChildrenOf = get_the_ID();
          }

          wp_list_pages(array(
            // Don't output the title list item
            'title_li' => NULL,
            // Only list children of the page ID
            'child_of' => $findChildrenOf,
            // Listen to the order defined in the CMS
            'sort_column' => 'menu_order',
          ));
        ?>
      </ul>
    </div>

    <?php } ?>

    <div class="generic-content">
      <?php the_content(); ?>
    </div>

  </div>

<!-- Find the footer.php file and display -->
<?php get_footer(); ?>