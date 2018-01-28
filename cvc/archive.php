<?php
get_header();

class CVC_Archive {

    public  $states = array();
    private $row_counter = 0;


    /*
     * Constructor
     * --------------------
     */

    function __construct($wp_query) {

        $this->wp_query       = $wp_query;
        $this->archive_title  = post_type_archive_title('', false);

        foreach ($wp_query->posts as $post) {
            $post_terms = wp_get_post_terms($post->ID, 'post-state');

            if (sizeOf($post_terms) > 0) {

                $first_term = $post_terms[0];

                if ($first_term->slug) {
                    array_push($this->states, $first_term);
                }
            }
        }
    }


    /*
     * Has Time State
     * --------------------
     */

    function has_time_state($target_slug) {

        for ($i=0; $i < count($this->states); $i++) {
            if ($this->states[$i]->slug == $target_slug) {
                return true;
            }
        }

        return false;
    }

    function has_current_and_past_posts() {

        if ($this->has_time_state('past') && $this->has_time_state('current')) {
            return true;
        } else {
            return false;
        }
    }


    function section_wrapper ($is_opening_tag) {

        if ($is_opening_tag && $this->row_counter % 2 == 0) {
            echo '<section class="row"> <!-- Add opening section row tag if is even. Now at '.$this->row_counter.' -->';
        }

        if (!$is_opening_tag) {

            if ($this->row_counter % 2 != 0) {
                echo "</section>  <!-- Close row if it is odd. Now at ".$this->row_counter." -->";
            }

            $this->row_counter++;
        }
    }

    function do_loop($target_post_state = false) {

        if (have_posts()) {

            if ($target_post_state) {
                echo "<h2>".$target_post_state." ".$this->archive_title."</h2>";
            }

            while (have_posts()) {
                the_post();

                if ($target_post_state) {
                    $post_terms  = wp_get_post_terms(get_the_ID(), 'post-state');
                    $target_slug = strtolower($target_post_state);

                    if (isset($post_terms[0]) && $target_slug == $post_terms[0]->slug) {
                        continue;
                    }
                }
                ?>

                <?php $this->section_wrapper(true);?>

                <article  <?php post_class(array('small-12','medium-6','columns','text-left')); ?>>
                    <a href="<?php the_permalink();?>">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('full');
                        } else {
                            echo "<img src='http://placehold.it/300x200&text=Image+Coming+Soon'/>";
                        }
                        ?>
                    </a>

                    <h5>
                        <a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>">
                            <?php the_title(); ?>
                        </a>
                    </h5>
                    <p>
                        <?php
                        if (has_excerpt()) {
                            the_excerpt();
                        }
                        ?>
                    </p>
                </article>

                <?php $this->section_wrapper(false);?>


                <?php
            }
        }
        wp_reset_query();

        if ($this->row_counter % 2 != 0) {
            echo "</section>  <!-- Close row to end uneven data. Now at ".$this->row_counter." -->";
        }

        $this->row_counter = 0;
    }

}

$archive = new CVC_Archive($wp_query);

?>

    <main class="row">
        <div class="small-12 columns">
            <br>
            <?php

            // If Current & Past
            if ($archive->has_current_and_past_posts()) {
                $archive->do_loop("Current");
                $archive->do_loop("Past");
            }

            // If No Current & Past
            if (!$archive->has_current_and_past_posts()) {
                $archive->do_loop();
            }

            ?>

        </div>
    </main>

<?php
get_footer();
?>