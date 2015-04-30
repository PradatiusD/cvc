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
                the_post_thumbnail('full');
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

  /*
   * Has Post With Specified State
   * --------------------------------
   * This function loops through the wp_query object
   * and determines if this array of posts has at least
   * one of the state (such as current/past/upcoming)
   * 
   */

  function has_post_with_state($state) {
    global $wp_query;

    $posts = $wp_query->posts;

    $posts_with_state = 0;

    for ($i=0; $i < count($posts) - 1; $i++) {
      $post = $posts[$i];
      $post_terms = wp_get_post_terms($post->ID, 'post-state');

      if (sizeOf($post_terms) > 0) {
        if ($post_terms[0]->slug == $state) {
          $posts_with_state++;
        }
      }
    }

    return ($posts_with_state > 0);
  }

?>

  <main class="row">
    <div class="small-12 columns">
      <?php the_breadcrumb(); ?>

      <?php if (has_post_state_taxonomy() && has_post_with_state('current')) :?>
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

      <?php if (has_post_state_taxonomy() &&  has_post_with_state('past')): ?>

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