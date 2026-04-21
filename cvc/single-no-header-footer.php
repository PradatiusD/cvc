<?php
/**
 * Template Name: No Header or Footer
 * Template Post Type: post
 */

// This hook loads styles and scripts normally found in header.php
wp_head(); 
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body <?php body_class(); ?>>
    <main id="primary" class="site-main row">
        <div class="small-12 columns">
            <main id="primary" class="site-main">
                <?php
                while ( have_posts() ) :
                    the_post();
                    the_content();
                endwhile;
                ?>
            </main>
        </div>
    </main>
<?php 
// This hook loads scripts normally found in footer.php
wp_footer(); 
?>
</body>
</html>