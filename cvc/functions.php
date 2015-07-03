<?php

function check_errors () {
  error_reporting(E_ALL);
  ini_set('display_errors', 1); 
}

if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
  // For Debugging on Localhost
  check_errors();
  
  // For live reloading
  function local_livereload(){
    wp_register_script('livereload', 'http://localhost:35729/livereload.js', null, false, true);
    wp_enqueue_script('livereload');    
  }
  add_action( 'wp_enqueue_scripts', 'local_livereload');
}

include_once('lib/foundation_breadcrumb.php');
include_once('lib/foundation_nav_walker.php');
include_once('lib/cvc_sub_nav.php');

function header_scripts () {
  wp_enqueue_style( 'theme-styles', get_stylesheet_uri() );
  wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,400|Roboto:400,700,400italic,700italic,300');
  wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
  wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts','header_scripts');

// 470x314 size for archive pages
add_image_size('archive-image', '470', '314', false); //uncropped
add_image_size('archive-image-cropped', '470','314', true); //cropped


function register_header_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'register_header_menu' );


function add_footer_widgets() {

  $common = array(
    'before_widget' => '<div class="small-12 medium-3 columns">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="rounded">',
    'after_title' => '</h4>'
  );

  for ($i=1; $i <= 4; $i++) { 

    register_sidebar(
      array_merge($common, array(
        'name' => 'Footer Widget Area '.$i,
        'id' => 'footer'.$i
        )
      )
    );
  }
}

add_action( 'widgets_init', 'add_footer_widgets' );


function cvc_display_map(){
  echo '<div id="map-canvas" style="height:600px;"></div>';
  wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?v=3.exp', false, '3.0', true);
  wp_enqueue_script( 'cvc-map', get_stylesheet_directory_uri().'/js/cvc-map.js', array('google-map'), '1.0', true);

}

add_shortcode( 'cvc_map', 'cvc_display_map' );