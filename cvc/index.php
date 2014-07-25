<?php get_header();?>
	<main class="row">
		<div class="small-12 columns">
			<?php the_breadcrumb(); ?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				
				<?php if(!is_front_page()):?>
					<h1>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php the_title(); ?>
						</a>
					</h1>
				<?php endif;?>
				
					<?php
					
					$subheader = types_render_field("subtitle");
					if(strlen($subheader)>0):?>
						
						<h4 class="subheader"><?php echo $subheader; ?></h4>
					
					<?php endif;?>

				<?php the_post_thumbnail('full'); ?> 

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
