<?php 
get_header();
?>
  <div class="row">
    <?php the_breadcrumb(); ?>  
  </div>

<?php
$subnav = new CVC_Sub_Nav();
$artist_query = $subnav->fetch_related_artist_posts();
$subnav->build_sub_nav($artist_query);

wp_enqueue_script('cvc-gallery', get_stylesheet_directory_uri().'/js/exhibition-gallery.js', array('jquery'), '1.0.0', true);

get_footer();