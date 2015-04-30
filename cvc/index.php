<?php get_header();?>

  <main class="row">
    <div class="small-12 columns">
      <?php 
      if ( have_posts()) {
        while (have_posts()) {?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php
            the_post(); 

            the_breadcrumb(); 

            $subheader = types_render_field("subtitle", array());

            if (strlen($subheader)>0) {
              echo "<small class='subheader'>".$subheader."</small>";
            }

            the_content('Read more...');

          }?>
        </article><?php
      } else {
        _e('Sorry, no posts matched your criteria.');
      }
      ?>
    </div>
  </main>
<?php get_footer();?>