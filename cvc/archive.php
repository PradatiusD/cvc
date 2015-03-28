<?php get_header();?>

<?php

  function section_wrapper ($is_opening_tag) {
    global $row_counter;

    if ($is_opening_tag && $row_counter % 2 == 0) {
      echo '<section class="row"> <!-- Add opening section row tag if is even. Now at '.$row_counter.' -->';   
    }

    if (!$is_opening_tag) {
      if ($row_counter % 2 != 0) {
        echo "</section>  <!-- Close row if it is odd. Now at ".$row_counter." -->";
      }

      $row_counter++;
    }
  }

  function archive_content($post) {
    global $row_counter;

    ob_start();?>

      <?php section_wrapper(true);?>

      <article  <?php post_class(array('small-12','medium-6','columns','text-left')); ?>>
        <a href="<?php the_permalink();?>">
          <?php 
            if (has_post_thumbnail()) {
                the_post_thumbnail('archive-image-cropped');
            } else {
              echo "<img src='http://placehold.it/300x200&text=Image+Coming+Soon'/>";
            }
          ?>
        </a>

        <h5>
          <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
        </h5>
        <p>
          <?php if (has_excerpt()) {the_excerpt();};?> 
        </p>
      </article>

      <?php section_wrapper(false);?>
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
        $row_counter = 0;
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

        <?php section_wrapper(false);?>

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