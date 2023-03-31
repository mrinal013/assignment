<?php
get_header();
?>
<div class="container">
    <div class="row gx-5">
<?php
$post_types = get_post_types();
asort( $post_types );
foreach ( $post_types as $name ) {

    $args = array(
        'post_type' => $name,
    );
    
    // Custom query. 
    $query = new WP_Query( $args );
    // Check that we have query results. 
    if ( $query->have_posts() ) {
        
        // Start looping over the query results. 
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/card', $name, array( 'name' => $name ) );
        }
        
    }
    // Restore original post data. 
    wp_reset_postdata();
}
?>
    </div>
</div>
<?php

get_footer();
