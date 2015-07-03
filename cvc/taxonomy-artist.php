<?php
get_header();
?>

<div class="row">
  <header class="archive-header">
    <?php the_breadcrumb(); ?>
    <h1><?php echo $wp_query->queried_object->name; ?></h1>
    <h2 class="subheader"><?php echo $wp_query->queried_object->description; ?></h2>
  </header>  
</div>

<?php

$subnav = new CVC_Sub_Nav();
$subnav->build_sub_nav($wp_query);


get_footer();
?>