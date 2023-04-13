<?php
/**
 * Template Name: Movie Template
 */
get_header();

$movies_args = array(
    'post_type' => 'movie'
);
$movies = get_posts( $movies_args );
// print_r($movies);
?>

<div class="container">
    
        <?php
        if ( ! empty( $movies ) ) {
            foreach ( $movies as $movie ) {
                $movie_id = $movie->ID;
                $movie_title = get_post_meta( $movie_id, '_movie_title', true );
                ?>
                <div class="row border border-primary my-3">
                    <div class="col-6 border-end"><?php echo $movie->post_content; ?></div>
                    <div class="col-6"><?php echo $movie_title; ?></div>
                </div>
                <?php
            }
        }
        ?>
        
    
</div>

<?php
get_footer();
