<?php get_header();?>
	<main class="row">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<h1>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php the_title(); ?>
							</a>
						</h1>
						<?php
							the_content('Read more...');
						?>
		<?php endwhile;?>
		<?php else: ?>
			<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
		<?php endif; ?>
	</main>
<?php get_footer();?>
