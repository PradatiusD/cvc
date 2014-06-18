<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo get_bloginfo(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
		<meta name="description" content="<?php echo bloginfo('description');?>">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" type="image/x-icon" />
		<?php wp_enqueue_script("jquery"); ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div class="wrapper">
			<header>
				<div class="row">
					<div class="small-12 columns">
						<nav class="top-bar" data-topbar>
							<ul class="title-area">
								<li class="name">
									<a href="<?php echo home_url(); ?>">
										<img src="<?php echo get_stylesheet_directory_uri() ?>/img/cvc-logo.png"/>
										<span><?php bloginfo('name'); ?></span>
									</a>
								</li>
								<li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
							</ul>

							<section class="top-bar-section">
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
							</section>
						</nav>
					</div>
				</div>
			</header>