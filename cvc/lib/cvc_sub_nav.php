<?php

class CVC_Sub_Nav {

  private $taxonomy_name = 'artist';
  
  private $exhibitions    = array();
  private $press_releases = array();
  private $events         = array();

  private $counter = 0;

  /*
   * Returns the First artist result from a taxonomy search from the post ID.
   */

  function get_related_artist () {
    global $wp_query;
    
    $post_id = $wp_query->queried_object->ID;
    $related_artists = wp_get_post_terms($post_id, $this->taxonomy_name);
    return $related_artists[0];
  }

  /*
   * Make a new query including these
   */

  function fetch_related_artist_posts () {

    $related_artist = $this->get_related_artist();
    $related_artist_slug = $related_artist->slug;

    $args = array(
      'artist' => $related_artist_slug,
    );

    $related_artist_posts = new WP_Query($args);

    return $related_artist_posts;

    // Reset Post Data
    wp_reset_postdata();
  }

  /*
   * Grab Image From Attachment ID
   * ------------------------
   * 
   */

  function create_image_from_attachment_id ($attachment_id) {

    $image         = wp_get_attachment_image_src( $attachment_id, 'full');
    $standard_meta = wp_get_attachment_metadata(  $attachment_id);
    $cvc_metadata  = get_post_custom($attachment_id);

    if (isset($image[0])) {
      return '<img src="'.$image[0].'" data-attachment-id="'.$attachment_id.'" data-meta=\''.json_encode($cvc_metadata).'\'/>';
    } else {
      return '';
    }
  }

  /*
   * Create Gallery
   * ------------------------
   * Regex that turns a shortcode back into a comma separated list
   * of attachment IDs, which are used to generate all the images seen
   * on a particular page.
   */

  function create_gallery ($post_body) {

    $pattern = '/\[vc_gallery .* images="(.*?)" .*\]/';
    $replacement = '$1';
    $gallery_string = preg_replace($pattern, $replacement, $post_body);

    $gallery_string = explode(",", $gallery_string);

    $images = '';

    for ($i=0; $i < sizeOf($gallery_string); $i++) {
      $attachment_id = $gallery_string[$i];
      $images .= $this->create_image_from_attachment_id($attachment_id);
    }

    return '<section class="cvc-gallery">'.$images.'</section>';
  }

 
  /*
   * Create a Subnavigation Tab
   * ----------------
   */

  function create_tab($posts_array) {

    ob_start(); 

      if (sizeof($posts_array) > 0):

        $post_type_title = get_post_type_object($posts_array[0]->post_type);
        $this->counter++;
        ?>
          <li class="tab-title <?php echo ($this->counter === 1) ?'active': '';?>">
            <a href="#panel<?php echo $this->counter;?>"><?php echo $post_type_title->labels->name; ?></a>
          </li>
        <?php

      endif;

    $content = ob_get_clean();
    echo $content;
  }

  /*
   * Create a tab content
   */

  function create_tab_content($posts_array) {

    ob_start();

    if (sizeof($posts_array) > 0):
      $this->counter++;
    ?>
    <div class="content <?php echo ($this->counter === 1) ?'active': '';?>" id="panel<?php echo $this->counter;?>">
      <?php
        foreach ($posts_array as $post):?>

          <h2><?php echo $post->post_title;?></h2>
          <h4 class="subheader"><?php echo do_shortcode("[types field='subtitle' id='".$post->ID."']");?></h4>

          <?php
          echo get_the_post_thumbnail( $post->ID, 'full');

          // Return the content without vc_galleries
          $content_without_gallery = preg_replace('/\[vc_gallery .* images=".*?" .*\]/','', $post->post_content);
          echo apply_filters('the_content', $content_without_gallery);

          // Return the custom gallery
          $gallery = $this->create_gallery($post->post_content);
          echo $gallery;

        endforeach;
      ?>
    </div>
    <?php
    endif;
    $content = ob_get_clean();
    echo $content;    
  }

  function build_sub_nav ($artist_query) {

    if ($artist_query->have_posts()):
      while ($artist_query->have_posts()) : $artist_query->the_post();

        $post = $artist_query->post;

        if ($post->post_type === 'exhibition') {
          array_push($this->exhibitions, $post);
        } else if ($post->post_type === 'event') {
          array_push($this->events, $post);
        } else if ($post->post_type === 'press-release') {
          array_push($this->press_releases, $post);
        }

      endwhile;
    endif;

  ?>
    <div class="row">
      <div class="small-12 columns">
        <hr>
        <ul class="tabs" data-tab>
          <?php 
            $this->create_tab($this->exhibitions);
            $this->create_tab($this->press_releases);
            $this->create_tab($this->events);
          ?>
        </ul>
        <div class="tabs-content">
          <?php
            // Reset Counter
            $this->counter = 0;
            $this->create_tab_content($this->exhibitions);
            $this->create_tab_content($this->press_releases);
            $this->create_tab_content($this->events);
          ?>
        </div>
      </div>
    </div>
  <?php
  }
};