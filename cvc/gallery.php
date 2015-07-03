<?php

  require_once("../../../wp-load.php");

  if (isset($_GET['id'])) {

    $attachment_id = $_GET['id'];

    $image         = wp_get_attachment_image_src( $attachment_id, 'full');
    $standard_meta = wp_get_attachment_metadata( $attachment_id);
    $cvc_metadata  = get_post_custom($attachment_id);

    $send_data = array(
      "src" => $image,
      "meta"=> $standard_meta,
      "custom" => $cvc_metadata
    );
    wp_send_json($send_data);
  }
?>