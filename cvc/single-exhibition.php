<?php 
get_header();
?>
  <div class="row">
    <?php the_breadcrumb(); ?>  
  </div>

<?php
$subnav = new CVC_Sub_Nav();
?>

  <main class="row">
    <div class="small-12 columns">
      <?php 
      if ( have_posts()) {
        while (have_posts()) {?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php
            the_post(); 
            $content = get_the_content();
            $subnav->content_without_gallery($content);
            $with_gallery = $subnav->create_gallery($content);
            echo $with_gallery;
          }?>
        </article><?php
      } else {
        _e('Sorry, no posts matched your criteria.');
      }
      ?>
    </div>
  </main>

<?php
wp_enqueue_script('images-loaded', "https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.1.8/imagesloaded.pkgd.min.js", array('jquery'),'3.1.8', true);
wp_enqueue_script('cvc-gallery', get_stylesheet_directory_uri().'/js/exhibition-gallery.js', array('jquery'), '1.0.0', true);

get_footer();