<?php

	function check_errors () {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);	
	}

	include('lib/foundation_breadcrumb.php');
	include('lib/foundation_nav_walker.php');

	function header_scripts () {
		wp_enqueue_style( 'theme-styles', get_stylesheet_uri() );
		wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
		wp_enqueue_script('jquery');
	}

	add_action('wp_enqueue_scripts','header_scripts');

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

	function top_five_images () {
		ob_start();
		?>
		<?php if (is_front_page()): ?>

			<style>
				.vc_spanFifths {
					width: 18% !important;
					float: left !important;
					text-align: center !important;
				}

				.vc_spanFifths .wpb_single_image {
					margin-bottom: 0;
				}

				@media screen and (max-width: 480px) {
					.vc_spanFifths {
						margin-left: 2% !important;
					}					
					.vc_spanFifths p {
						font-size: 0.8em;
					}					

				}
			</style>

			<script>
				(function($){
					var $firstRow = jQuery('.wpb_row').eq(0);
					$firstRow.find('.vc_span1').remove()
					$firstRow.find('.vc_span2').addClass('vc_spanFifths');
				})(jQuery);
			</script>

		<?php endif; ?>
		<?php
		echo ob_get_clean();
	}

	add_action('wp_footer','top_five_images');