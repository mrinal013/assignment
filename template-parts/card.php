<div class="col-4 border">
    <h3><?php echo esc_html( get_the_title() ); ?></h3>
    <span class="badge badge-pill badge-primary bg-info"><?php echo esc_html( $args['name'] ); ?></span>
    <p><?php the_excerpt(); ?></p>
    <a href="<?php the_permalink(); ?>" class="btn btn-info"><?php echo esc_html_e( 'Go Somewhere', 'assignment' ); ?></a>
</div>