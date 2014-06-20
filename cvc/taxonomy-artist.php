<?php
	get_header();
	$exhibitions    = array();
	$press_releases = array();
	$events         = array();
	global $wp_query;
?>

<?php

	if ( have_posts()):
		while ( have_posts() ) : the_post();

			if ($post->post_type === 'exhibition') {
				array_push($exhibitions, $post);
			} else if ($post->post_type === 'event') {
				array_push($events, $post);
			} else if ($post->post_type === 'press-release') {
				array_push($press_releases, $post);
			}

		endwhile;
	endif;

	$counter = 0;
	function create_tab($posts_array) {
		global $counter;

		$post_type_title = get_post_type_object($posts_array[0]->post_type);

		ob_start(); 

		if (sizeof($posts_array) > 0):
			$counter++;
		?>
			<li class="tab-title <?php echo ($counter === 1) ?'active': '';?>">
				<a href="#panel<?php echo $counter;?>"><?php echo $post_type_title->labels->name; ?></a>
			</li>
		<?php
		endif;
		$content = ob_get_clean();
		echo $content;
	}

	function create_tab_content($posts_array) {
		global $counter;
		ob_start(); 

		if (sizeof($posts_array) > 0):
			$counter++;
		?>
		<div class="content <?php echo ($counter === 1) ?'active': '';?>" id="panel<?php echo $counter;?>">
			<?php
				foreach ($posts_array as $post):?>

					<h2><?php echo $post->post_title;?></h2>
					<h4 class="subheader"><?php echo do_shortcode("[types field='subtitle' id='".$post->ID."']");?></h4>

					<?php
					echo get_the_post_thumbnail( $post->ID, 'full', $attr );
					echo apply_filters('the_content', $post->post_content);
				endforeach;
			?>
		</div>
		<?php
		endif;
		$content = ob_get_clean();
		echo $content;		
	}
?>

<div class="row">
	<div class="small-12 columns">
		<?php the_breadcrumb();?>
		<div class="archive-header">
			<h1><?php echo $wp_query->queried_object->name; ?></h1>
			<h2 class="subheader"><?php echo $wp_query->queried_object->description; ?></h2>
		</div>
		<hr>
		<ul class="tabs" data-tab>
			<?php 
				create_tab($exhibitions);
				create_tab($press_releases);
				create_tab($events);
			?>
		</ul>
		<div class="tabs-content">
			<?php
				// Reset Counter
				$counter = 0;
				create_tab_content($exhibitions);
				create_tab_content($press_releases);
				create_tab_content($events);
			?>
		</div>
	</div>
</div>
<?php get_footer();?>