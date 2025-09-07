<div class="ads" itemscope itemtype="https://schema.org/WPAdBlock">
    
    <?php
        $ads_query = new WP_Query(array(
            'post_type'         => 'anuncio',
            'posts_per_page'    => 1,
            'orderby'           => 'rand'
        ));
        if ($ads_query->have_posts()) :
            while ($ads_query->have_posts()) : $ads_query->the_post();
                if ($ad_image = get_the_post_thumbnail_url(get_the_ID(), 'full')) :
                    // Buscar campos ACF
                    $link_anuncio = get_field('link_anuncio');
                    $origem_campanha = get_field('origem_campanha');
                    $tipo_midia = get_field('tipo_midia');
                    $nome_campanha = get_field('nome_campanha');
                    
                    // Construir URL com parâmetros UTM
                    $url_final = $link_anuncio ? $link_anuncio : get_permalink();
                    
                    if ($link_anuncio && ($origem_campanha || $tipo_midia || $nome_campanha)) {
                        $params = array();
                        
                        if ($origem_campanha) {
                            $params['utm_source'] = urlencode($origem_campanha);
                        }
                        
                        if ($tipo_midia) {
                            $params['utm_medium'] = urlencode($tipo_midia);
                        }
                        
                        if ($nome_campanha) {
                            $params['utm_campaign'] = urlencode($nome_campanha);
                        }
                        
                        if (!empty($params)) {
                            $separator = strpos($url_final, '?') !== false ? '&' : '?';
                            $url_final .= $separator . http_build_query($params);
                        }
                    }
    ?>
                    
                    <a href="<?= esc_url($url_final); ?>" itemprop="url" <?= $link_anuncio ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                        <img src="<?= esc_url($ad_image); ?>" alt="<?php the_title_attribute(); ?>" itemprop="image">
                    </a>
    <?php
                endif;
            endwhile;
            wp_reset_postdata();
        endif;
    ?>
</div>