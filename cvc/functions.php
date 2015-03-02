<?php

	function check_errors () {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);	
	}

	class Foundation_Navigation_Walker extends Walker_Nav_Menu {

		// add classes to ul sub-menus
		function start_lvl(&$output, $depth) {
			// depth dependent classes
			$indent = ( $depth > 0 ? str_repeat("\t", $depth) : '' ); // code indent
			
			// build html
			$output .= "\n" . $indent . '<ul class="dropdown">' . "\n";
		}
	}

	if (!function_exists('Foundation_Set_Dropdown')):

		function Foundation_Set_Dropdown($sorted_menu_items, $args) {
			$last_top = 0;
			foreach ($sorted_menu_items as $key => $obj) {
			// it is a top lv item?
				if (0 == $obj->menu_item_parent) {
				// set the key of the parent
					$last_top = $key;
				} else {
					$sorted_menu_items[$last_top]->classes['dropdown'] = 'has-dropdown';
				}
			}

			return $sorted_menu_items;
		}

	endif;

	add_filter('wp_nav_menu_objects', 'Foundation_Set_Dropdown', 10, 2);

	function register_header_menu() {
		register_nav_menu('header-menu',__( 'Header Menu' ));
	}
	add_action( 'init', 'register_header_menu' );


	function the_breadcrumb() {

		global $post;
		$cat = get_the_category();
		$crumb = '';

		if (!is_home() && !is_front_page()) {

			$crumb .= '<li><a href="'. home_url(). '">Home</a>';

			if (is_category() || is_single()) {

				$crumb .= '<li>';
				$crumb .= '<a href="'.get_post_type_archive_link(). '">' . get_post_type(). "</a>";

				if (is_single()) {
					$crumb .= '</li>';
					$crumb .= '<li class="current">'. get_the_title() . '</li>';
				}
			} else if(is_post_type_archive()){

				$crumb .= '<li class="current">'. get_post_type() .'</li>';

			} elseif (is_page()) {

				if($post->post_parent){
					$anc = get_post_ancestors( $post->ID );
					$title = get_the_title();
					foreach ( $anc as $ancestor ) {
						$output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li> <li class="separator">/</li>';
					}
					$crumb .= $output;
					$crumb .= '<strong title="'.$title.'"> '.$title.'</strong>';
				} else {
					$crumb .= '<li class="current">'.get_the_title().'</li>';
				}
			}
		}

		elseif (is_tag()) {single_tag_title();}
		elseif (is_day()) {$crumb .="<li>Archive for "; the_time('F jS, Y'); $crumb .='</li>';}
		elseif (is_month()) {$crumb .="<li>Archive for "; the_time('F, Y'); $crumb .='</li>';}
		elseif (is_year()) {$crumb .="<li>Archive for "; the_time('Y'); $crumb .='</li>';}
		elseif (is_author()) {$crumb .="<li>Author Archive"; $crumb .='</li>';}
		elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {$crumb .= "<li>Blog Archives"; $crumb .='</li>';}
		elseif (is_search()) {$crumb .="<li>Search Results"; $crumb .='</li>';}
		
		if(strlen($crumb)>0) {
			echo '<ul class="breadcrumbs">'. $crumb .'</ul>';
		}
		
	}

	function add_footer_widgets() {

		$common = array(
			'before_widget' => '<div class="small-12 medium-3 columns">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="rounded">',
			'after_title' => '</h4>'
		);

		register_sidebar(
			array_merge($common, array(
				'name' => 'Footer Widget Area 1',
				'id' => 'footer1'
				)
			)
		);

		register_sidebar(
			array_merge($common, array(
				'name' => 'Footer Widget Area 2',
				'id' => 'footer2'
				)
			)
		);

		register_sidebar(
			array_merge($common, array(
				'name' => 'Footer Widget Area 3',
				'id' => 'footer3'
				)
			)
		);

		register_sidebar(
			array_merge($common, array(
				'name' => 'Footer Widget Area 4',
				'id' => 'footer4'
				)
			)
		);
	}

	add_action( 'widgets_init', 'add_footer_widgets' );

	/**
	 * For Custom Gallery
	 */
	remove_shortcode('gallery');
	add_shortcode('gallery', 'cvc_gallery_shortcode');

	function cvc_gallery_shortcode($atts) {
		
		global $post;
		
		if ( ! empty( $atts['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $atts['orderby'] ) )
				$atts['orderby'] = 'post__in';
			$atts['include'] = $atts['ids'];
		}
		
		extract(shortcode_atts(array(
			'orderby' => 'menu_order ASC, ID ASC',
			'include' => '',
			'id' => $post->ID,
			'itemtag' => 'dl',
			'icontag' => 'dt',
			'captiontag' => 'dd',
			'columns' => 3,
			'size' => 'medium',
			'link' => 'file'
			), $atts));
		
		
		$args = array(
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'post_mime_type' => 'image',
			'orderby' => $orderby
			);
		
		if ( !empty($include) )
			$args['include'] = $include;
		else {
			$args['post_parent'] = $id;
			$args['numberposts'] = -1;
		}
		
		$images = get_posts($args);
		ob_start();
		?>
		<section class="gallery">

			<?php foreach ($images as $image):     

					$caption     = $image->post_excerpt;					
					$description = $image->post_content;
					if($description == '') $description = $image->post_title;
					$image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);
					$big_image_src   = wp_get_attachment_image_src($image->ID, 'full');
					$big_image_src   = $big_image_src[0];
					$small_image_src = wp_get_attachment_image_src($image->ID, 'medium');
					$small_image_src = $small_image_src[0];
				?>
				<a href="<?php echo $big_image_src;?>" class="lightbox">
					<img class="th" src="<?php echo $small_image_src;?>" alt="<?php echo $description;?>">
				</a>
			<?php endforeach; ?>
		</section>

		<?php
		$content = ob_get_clean();
		return $content;
	}

	function cvc_display_map( $atts ){
		ob_start();?>


    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
		var map;
		function initialize() {

			var cvcLatLng = new google.maps.LatLng(25.802085, -80.203986)
			
			var mapOptions = {
				zoom: 16,
				center: cvcLatLng
			};
			
			map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

			var marker = new google.maps.Marker({
				position: cvcLatLng,
				map: map,
				title: 'CVC'
			});

			var contentString = '<div id="content" style="width:250px;">'+
									'<hr><div id="bodyContent">'+
										'<p><address>'+
											'541 NW 27th Street<br/>Miami, FL 33127<br/>305-571-1415' +
										'</address></p>'+
									'</div><hr>'+
								'</div>';

			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});

			google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker);
			});

		}

		google.maps.event.addDomListener(window, 'load', initialize);

    </script>
    <div id="map-canvas" style="height:600px;"></div>

	<?php
		$content = ob_get_clean();
		return $content;
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