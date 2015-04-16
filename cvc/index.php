<?php get_header();?>

  <main class="row">
    <div class="small-12 columns">
      <?php 
      if ( have_posts()) {
        while (have_posts()) {
          the_post(); 

          the_breadcrumb(); 

          $subheader = types_render_field("subtitle", array());

          if (strlen($subheader)>0) {
            echo "<small class='subheader'>".$subheader."</small>";
          }

          the_content('Read more...');

        }
      } else {
        _e('Sorry, no posts matched your criteria.');
      }
      ?>
    </div>
  </main>
<?php get_footer();?>