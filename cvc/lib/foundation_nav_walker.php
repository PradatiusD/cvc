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

if (!function_exists('Foundation_Set_Dropdown')){

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
}

add_filter('wp_nav_menu_objects', 'Foundation_Set_Dropdown', 10, 2);