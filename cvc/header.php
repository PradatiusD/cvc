<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo get_bloginfo(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo bloginfo('description');?>">
    <link rel="icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" type="image/x-icon" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="wrapper mobile-nav-push">
    <div class="main-wrapper">
        <header>
            <!-- Header Social Icons -->
            <nav class="row">
                <div class="small-12 columns">
                    <nav class="top-bar" data-topbar>

                        <a href="#mobile-menu" class="mobile-menu-nav-link">&#9776;</a>

                        <ul class="title-area">
                            <li class="name">
                                <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/img/cvc-logo-black.png"/>
                                </a>
                            </li>
                        </ul>

                        <section class="top-bar-section">
                            <?php
                            $header_menu = array(
                                'theme_location' => 'header-menu',
                                'container'      => false,
                                'depth'          => 2,
                                'items_wrap'     => '<ul id="%1$s" class="right %2$s">%3$s</ul>',
                                'walker'         => new Foundation_Navigation_Walker()
                            );
                            wp_nav_menu($header_menu);
                            ?>
                        </section>
                    </nav>
                </div>
            </nav>
        </header>
        <section id="mobile-menu" class="panel panel-mobile-side-menu" role="navigation" style="display: none;">
            <?php
            $mobile_menu = array(
                'theme_location' => 'header-menu',
                'container'      => false,
                'depth'          => 2
            );
            wp_nav_menu($mobile_menu);
            ?>
        </section>