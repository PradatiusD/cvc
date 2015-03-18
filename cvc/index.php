<?php get_header();

$row_counter = 0;
?>

  <main class="row">
    <div class="small-12 columns">
      <?php the_breadcrumb(); ?>
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <?php if(!is_front_page() && is_post_type_archive()):?>

          <!-- Add opening section row tag if is even -->
          
          <?php if ($row_counter % 2 == 0): ?>
            <section class="row">
          <?php endif; ?>

          <!-- Image with the text below -->

          <article class="small-12 medium-6 columns text-center">
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
              <?php
                if (has_excerpt()) {
                  echo get_the_excerpt();
                }
              ?> 
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

        <?php endif;?>
        
        <?php $subheader = types_render_field("subtitle");?>

        <?php if(strlen($subheader)>0): ?>
          <small class="subheader"><?php echo $subheader; ?></small>        
        <?php endif; ?>

        <?php
          if (!is_post_type_archive()) {
              the_content('Read more...');
          }
        ?>

      <?php endwhile;?>
      <?php else: ?>
          <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
      <?php endif; ?>
    </div>
  </main>
<?php get_footer();?>