<section class="services paddingContent" itemscope itemtype="https://schema.org/WebPage">    
    <h1 itemprop="headline">Serviços</h1>
    <span class="subtitle" itemprop="alternativeHeadline">Conheça serviços especializados em HIV/AIDS e Infecções Sexualmente Transmissíveis (ISTs)</span>

    <article class="listServives" id="services-container">
        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = [
            'post_type'         => 'servicos',
            'posts_per_page'    => -1,
            'paged'             => $paged
        ];
        $services = new WP_Query($args);
        if ($services->have_posts()) :
            while ($services->have_posts()) :
                $services->the_post();
                ?>
                <figure itemscope itemtype="https://schema.org/Service">
                    <?php the_post_thumbnail('logos_services', ['itemprop' => 'image']); ?>
                    <figcaption>
                        <h2 itemprop="name"><?php the_title(); ?></h2>
                        <div itemprop="description"><?php the_content(); ?></div>
                        <!-- <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" itemprop="url">Saiba mais</a> -->
                    </figcaption>
                </figure>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </article>

    <nav class="pagination" itemscope itemtype="https://schema.org/SiteNavigationElement">
        <?php get_template_part('partials/pagination', null, ['query' => $services]); ?>
    </nav>
</section>