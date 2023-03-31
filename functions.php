<?php

/**
 * Add theme support
 * title-tag
 * html5
 *   - style
 *   - script
 */
function assignment_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', array( 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'assignment_theme_setup' );

/**
 * Add style.css
 * Add bootstrap5 stylesheet via CDN
 * Add bootstrap5 script in header via CDN
 * Add /src/js/app.js in footer
 */
function add_theme_scripts() {
    wp_enqueue_style( 'style', get_stylesheet_uri(), array(), filemtime( get_template_directory() . '/style.css' ) );
    
	wp_enqueue_style( 'bs-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), wp_get_theme()->get( 'Version' ), 'all' );
    
	wp_enqueue_script( 'bs-script', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array(), wp_get_theme()->get( 'Version' ), false );
    
    wp_enqueue_script( 'script', get_template_directory_uri() . '/src/js/app.js', array( 'bs-script' ), filemtime( get_template_directory() . '/src/js/app.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );

/**
 * Register a custom post type for 'book'
 *
 * @package assignment
 * @version 1.0
 */
add_action( 'init', 'register_book_cpt' );
function register_book_cpt() {
    $labels = array(
        'name'                     => __( 'Book', 'assignment' ),
    );

   $args = array(
      'labels'                => $labels,
      'public'                => true,
      'publicly_queryable'    => true,
      'supports'              => array( 'title', 'editor', 'excerpt' ),
      'rewrite'               => array( 'slug' => 'books' ),

   );

    register_post_type( 'book', $args );
}

/**
 * Register meta box for tagline.
 */
function wpdocs_register_meta_boxes() {
	add_meta_box( 'tagline-id', __( 'Tagline', 'assignment' ), 'tagline_callback', 'book' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );

/**
 * Render tagline metabox
 *
 * @param object $post
 */
function tagline_callback( $post ) {
    // Add nonce for security and authentication.
	wp_nonce_field( 'tagline_metabox', 'tagline_metabox_nonce' );

    $tagline = get_post_meta( $post->ID, '_tagline', true );

    ?>
    <textarea name="tagline" id="tagline" class="widefat" rows="10"><?php echo esc_textarea( $tagline ); ?></textarea>
    <?php

}

/**
 * Save tagline
 * 
 * @param int $post_id
 */
function save_tagline( $post_id ) {
    /*
    * We need to verify this came from the our screen and with proper authorization,
    * because save_post can be triggered at other times.
    */

    // Check if our nonce is set.
    if ( ! isset( $_POST['tagline_metabox_nonce'] ) ) {
        return $post_id;
    }

    $nonce = $_POST['tagline_metabox_nonce'];

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'tagline_metabox' ) ) {
        return $post_id;
    }

    /*
        * If this is an autosave, our form has not been submitted,
        * so we don't want to do anything.
        */
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // Check the user's permissions.
    if ( 'book' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        }
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Sanitize the user input.
	$tagline = sanitize_textarea_field( $_POST['tagline'] );

    // Update the meta field.
	update_post_meta( $post_id, '_tagline', $tagline );
}
add_action( 'save_post', 'save_tagline' );

/**
 * Filter document title
 * 
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string Filtered title.
 */
function assignment_filter_wp_title( $title ) {

    if ( 'book' === get_post_type() ) {
        $post_id = get_queried_object_id();
        $tagline = get_post_meta( $post_id, '_tagline', true );
        if( ! empty( $tagline ) ) {
            $title = $tagline;
        }
    }
    return $title;

}
add_filter( 'wp_title', 'assignment_filter_wp_title', 99 );

/**
 * Add book post type with main query
 */
/*
 * Sets the post types that can appear on archive pages.
 */
function assignment_post_types($query){
    
    $is_target_query = ! is_admin() && $query->is_main_query();
            
    if ( $is_target_query ) {
        $target_types = array( 'post', 'book' );
        $query->set( 'post_type', $target_types );
    }
}
add_action( 'pre_get_posts', 'assignment_post_types', 10, 1 );