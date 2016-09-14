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
  wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
  wp_enqueue_script('jquery');
}

function footer_scripts () {
  if (!is_single()) {
    return;
  }

  wp_enqueue_script('images-loaded', "https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.1.8/imagesloaded.pkgd.min.js", array('jquery'),'3.1.8', true);
  wp_enqueue_script('cvc-gallery', get_stylesheet_directory_uri().'/js/exhibition-gallery.js', array('jquery'), '1.0.0', true);
}

add_action('wp_enqueue_scripts','header_scripts');
add_action('wp_enqueue_scripts','footer_scripts');


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




function modify_nav_menu_items($items, $args){

  if ($args->theme_location == "header-menu") {

    ob_start();?>
      <li class="menu-item">
        <a href="https://www.facebook.com/Center-for-Visual-Communication-158082450882461/" target="_blank">
          <i class="fa fa-facebook-square"></i>
        </a>
      </li>
      <li class="menu-item">
        <a href="https://instagram.com/cvcmiami/" target="_blank">
          <i class="fa fa-instagram"></i>
        </a>        
      </li>
    <?php
    $items .= ob_get_clean();
  }

  return $items;
}

add_filter( 'wp_nav_menu_items', 'modify_nav_menu_items', 10, 2);

wp_enqueue_script('cvc-breadcrumb', get_stylesheet_directory_uri().'/js/breadcrumb.js', array('jquery'),'1.0.0', true);

function custom_gallery( $content ) {

  if (!is_single()) {
    return $content;
  }

  $content = preg_replace_callback('/\[vc_gallery.*?\]/', function ($matches) {

    $subnav  = new CVC_Sub_Nav();
    $gallery = $subnav->create_gallery($matches);

    return $gallery;
  }, $content);

  $content = preg_replace_callback('/<section class="cvc-gallery">[\s\S]*<!--gallery-end-->/i', function ($matches) {

    if (empty($matches)) {
      return '';
    }

    $gallery = $matches[0];
    $gallery = preg_replace('/(<br \/>|<p>|<\/p>)/', "", $gallery);
    return $gallery;

  }, $content);

  return $content;
}
add_filter('the_content','custom_gallery');


