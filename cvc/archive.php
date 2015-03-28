<?php get_header();?>

<?php

  $row_counter = 0;

  function archive_content($post) {
    global $row_counter;

    ob_start();?>

       <!-- Add opening section row tag if is even -->
      
      <?php if ($row_counter % 2 == 0): ?>
        <section class="row">
      <?php endif; ?>

      <article  <?php post_class(array('small-12','medium-6','columns','text-center')); ?>>
        <a href="<?php the_permalink();?>">
          <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('full');
            } else {
              echo "<img src='http://placehold.it/300x200&text=Image+Coming+Soon'/>";
            }
          ?>
        </a>
        <h5>
          <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
              <?php the_title(); ?>
          </a>
        </h5>
        <p>
          <?php if (has_excerpt()) {the_excerpt();};?> 
        </p>
      </article>

      <!-- Close row if it is odd -->
      <?php 
        if ($row_counter % 2 != 0) {
          echo "</section>";
        } else {
          $row_counter++;
        }
      ?>
  <?php
    echo ob_get_clean();
  };

  function is_past_post($post) {
    $terms = wp_get_post_terms( $post->ID, 'post-state');
    $is_past = false;

    foreach ($terms as $term) {
      if ($term->slug == 'past') {
        $is_past = true;
        break;
      }
    }
    return $is_past;
  }
?>

  <main class="row">
    <div class="small-12 columns">
      <?php the_breadcrumb(); ?>

      <h2>Current <?php post_type_archive_title();?></h2>
      <?php 
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post(); 
            if (!is_past_post($post)) {
              archive_content($post);              
            }
          }
        }
      ?>

      <h2>Past <?php post_type_archive_title();?></h2>
      <?php
         wp_reset_query();
         $row_counter = 0;

        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post(); 
            if (is_past_post($post)) {
              archive_content($post);              
            }

          }
        }
      ?>      
    </div>
  </main>
<?php get_footer();?>