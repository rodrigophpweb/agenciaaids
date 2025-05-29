<section class="services">
    <?php the_title('<span class="subtitle">','</span>')?>
    <h1>Conheça serviços especializados em HIV/AIDS e Infecções Sexualmente Transmissíveis (ISTs)</h1>
    <article class="listServives" id="services-container">
        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = [
            'post_type'         => 'servicos',
            'posts_per_page'    => 6,
            'paged'             => $paged
        ];
        $services = new WP_Query($args);
        if ($services->have_posts()) :
            while ($services->have_posts()) :
                $services->the_post();
                ?>
                <figure itemscope itemtype="http://schema.org/Service">
                    <?php the_post_thumbnail('logos_services', ['itemprop' => 'image']); ?>
                    <figcaption>
                        <h2 itemprop="name"><?php the_title(); ?></h2>
                        <p itemprop="description"><?php the_content(); ?></p>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" itemprop="url">Saiba mais</a>
                    </figcaption>
                </figure>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </article>
    <nav class="pagination" itemscope itemtype="http://schema.org/SiteNavigationElement">
        <?php get_template_part('partials/pagination', null, ['query' => $services]); ?>
    </nav>
</section>