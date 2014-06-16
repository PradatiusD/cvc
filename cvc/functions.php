<?php

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
				$crumb .= '<a href="'.get_category_link($cat[0]->cat_ID).'">' . $cat[0]->name . "</a>";

				if (is_single()) {
					$crumb .= '</li>';
					$crumb .= '<li class="current">'. get_the_title() . '</li>';
				}
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