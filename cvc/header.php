<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo get_bloginfo(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
		<link rel="icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" type="image/x-icon" />
		<?php wp_enqueue_script("jquery"); ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

		<nav class="top-bar" data-topbar>
			<ul class="title-area">
				<li class="name">
					<h1><a href="#"><?php echo get_bloginfo(); ?></a></h1>
				</li>
				<li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
			</ul>

			<section class="top-bar-section">
				<!-- Right Nav Section -->
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'header-menu',
						'container' => false,
						'depth' => 2,
						'items_wrap' => '<ul id="%1$s" class="right %2$s">%3$s</ul>',
						'walker' => new Foundation_Navigation_Walker()
					)
				);
				?>

				<!-- Left Nav Section -->
			<!-- 			
				<ul class="left">
					<li><a href="#">Logo</a></li>
				</ul> 
			-->
			</section>
		</nav>