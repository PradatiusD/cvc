<?php

get_header();
$args = array(
		'post_type' => 'exhibition'
	);

$the_query = new WP_Query($args);
?>
	<section class="home-header">
		<div class="row">
			<h1>Welcome to the Center for Visual Communication</h1>
			<h3>We organize and present art exhibitions in our Miami gallery located in Wynwood and at public venues in South Florida including The Arsht Center for Performing Arts, Florida International University, Miami Science Museum and Miami Dade College.</h3>
		</div>
	</section>
	<div class="row">
		<hr>
		<h4 class="text-center">Our Current Exhibitions</h4>
		<hr>
	</div>
	<main class="row">
		<?php
		
		if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); 
			$artist = wp_get_post_terms($post->ID, 'artist');
		?>
			<div class="small-12 columns">
				<h1>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_title(); ?>
					</a>
				</h1>
				<h4 class="subheader"><?php echo types_render_field("subtitle"); ?></h4>
			</div>
			<div class="small-12 medium-6 columns">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail('full'); ?> 
				</a>
			</div>
			<div class="small-12 medium-6 columns">
				<?php
					the_content('Read more...');
				?>
				<p>
					<a class="button secondary small" href="<?php echo get_term_link($artist[0]->term_id,'artist');?>">
						See press, events, and more from <?php echo $artist[0]->name;?>
						&nbsp; <i class="fa fa-angle-double-right"></i>
					</a>
				</p>
			</div>
			<div class="small-12 columns">
				<hr>
			</div>

			<?php endwhile;?>

			<?php else: ?>
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
			<?php endif; ?>
	</main>

<?php get_footer();?>