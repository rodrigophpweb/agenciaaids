<div class="ads" itemscope itemtype="https://schema.org/WPAdBlock">
    
        <a href="https://www.sescsp.org.br/projetos/oju-roda-sesc-de-cinemas-negros/?ads=agenciaaids" target="_blank" itemprop="url">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ads/sesc-sao-paulo-oju-roda-sesc-de-cinemas-negros.gif" alt="Banner - OJU - Roda Sesc de Cinemas Negros" >
        </a>
    
    <?php
        $ads_query = new WP_Query(array(
            'post_type'         => 'ads',
            'posts_per_page'    => 1,
            'orderby'           => 'rand'
        ));
        if ($ads_query->have_posts()) :
            while ($ads_query->have_posts()) : $ads_query->the_post();
                if ($ad_image = get_the_post_thumbnail_url(get_the_ID(), 'full')) :
    ?>
                    <a href="<?php the_permalink(); ?>" itemprop="url">
                        <img src="<?= esc_url($ad_image); ?>" alt="<?php the_title_attribute(); ?>" itemprop="image">
                    </a>
    <?php
                endif;
            endwhile;
            wp_reset_postdata();
        endif;
    ?>
</div>