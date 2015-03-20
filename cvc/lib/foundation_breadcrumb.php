<?php

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
