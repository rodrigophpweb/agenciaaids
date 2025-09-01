<?php

    $args = [
        'post_type' => $tpp['post_type'],
    ];
    
    $the_query = new WP_Query( $args ); 
    if ( $the_query->have_posts() ) : 
	    while ( $the_query->have_posts() ) :
		    $the_query->the_post();
		?>
		    
	    
        <?php endwhile; wp_reset_postdata(); ?>
    <?php else : ?>
	    <p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
    <?php 
endif; ?>
