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

      <article  <?php post_class(array('small-12','medium-6','columns','text-left')); ?>>
        <a href="<?php the_permalink();?>">
          <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('archive-image');
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
    $is_past = has_term( 'past', 'post-state');
    return $is_past;
  }

  function has_post_state_taxonomy () {
    $archive_name = get_queried_object();
    $archive_name = $archive_name->rewrite['slug'];

    if ($archive_name == 'exhibition' || $archive_name == 'event' || $archive_name == 'program') {
      return true;
    }
  }

?>

  <main class="row">
    <div class="small-12 columns">
      <?php the_breadcrumb(); ?>

      <?php if (has_post_state_taxonomy()): ?>
        <h2>Current <?php post_type_archive_title();?></h2>      
      <?php endif; ?>

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

      <?php if (has_post_state_taxonomy()): ?>
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
 
     <?php endif; ?>
    </div>
  </main>
<?php get_footer();?>