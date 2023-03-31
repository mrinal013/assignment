<?php
$tagline = get_post_meta( get_the_ID(), '_tagline', true );
if ( empty( $tagline ) ) {
    $tagline = get_the_excerpt();
}
?>
<div class="col-4 border">
        <h3><?php echo esc_html( get_the_title() ); ?></h3>
        <span class="badge badge-pill badge-primary bg-primary"><?php echo esc_html( $args['name'] ); ?></span>
        <p><?php echo esc_html( $tagline ); ?></p>
        <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php echo esc_html_e( 'Go Somewhere', 'assignment' ); ?></a>
</div>