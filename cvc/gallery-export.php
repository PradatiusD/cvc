<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('./../../../wp-load.php');

function get_data ($sql) {

    global $wpdb;
    $results = $wpdb->get_results($sql);
    return $results;
}

function maybeEncodeCSVField($string) {
    if(strpos($string, ',') !== false || strpos($string, '"') !== false || strpos($string, "\n") !== false) {
        $string = '"' . str_replace('"', '""', $string) . '"';
    }
    return $string;
}


$post_meta   = get_data('SELECT * FROM wp_pnkj_postmeta WHERE meta_key LIKE "wpcf-%"');
$attachments = get_data('SELECT ID, guid, post_title FROM wp_pnkj_posts WHERE post_type="attachment" and post_mime_type LIKE "image%"');


for ($i=0; $i < count($attachments); $i++) {

    $attachment_post_id = $attachments[$i]->ID;

    for ($j=0; $j < count($post_meta); $j++) {

        $post_meta_post_id = $post_meta[$j]->post_id;

        if ($post_meta_post_id === $attachment_post_id) {

            $meta_key = $post_meta[$j]->meta_key;
            $meta_value = $post_meta[$j]->meta_value;

            if ($meta_key && $meta_value) {
                $attachments[$i]->$meta_key = $meta_value;
            }
        }
    }
}


// output headers so that the file is downloaded rather than displayed
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="Gallery Export '.date("F j, Y").'.csv"');

// do not cache the file
header('Pragma: no-cache');
header('Expires: 0');

// create a file pointer connected to the output stream
$file = fopen('php://output', 'w');

// send the column headers
fputcsv($file, array('Post ID', 'Image URL', 'Image Title', 'Artist Name', 'Work Name', 'Year Made', 'Work Medium', 'Year'));

// Sample data. This can be fetched from mysql too

// output each row of the data
foreach ($attachments as $row){
    fputcsv($file, (array) $row);
}

exit();


?>