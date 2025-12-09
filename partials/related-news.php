<?php
// Busca posts relacionados na mesma categoria
$categories = get_the_category();
$related_posts = null;

if ($categories) {
    $category_id = $categories[0]->term_id;

    $related_posts = new WP_Query([
        'cat'                 => $category_id,
        'post__not_in'        => [get_the_ID()],
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
        'post_status'         => 'publish'
    ]);
}

// Se não encontrou posts na categoria, busca os 3 posts mais recentes
if (!$related_posts || !$related_posts->have_posts()) {
    $related_posts = new WP_Query([
        'post__not_in'        => [get_the_ID()],
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
        'post_status'         => 'publish',
        'orderby'             => 'date',
        'order'               => 'DESC'
    ]);
}

if ($related_posts && $related_posts->have_posts()) : 
    // Define o título baseado no tipo de post
    $post_type = get_post_type();
    $related_title = ($post_type === 'artigos') ? 'Artigos Relacionados' : 'Notícias Relacionadas';
?>
    <section class="related-posts paddingContent" aria-labelledby="related-posts-title">
        <h2 id="related-posts-title">
            <?php echo esc_html($related_title); ?>
        </h2>
        <div class="related-grid">
            <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                <article <?php post_class('related-card'); ?> itemscope itemtype="https://schema.org/NewsArticle">
                    <!-- URL do artigo -->
                    <meta itemprop="url" content="<?php echo esc_url(get_permalink()); ?>">
                    <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
                    
                    <!-- Autor -->
                    <div itemprop="author" itemscope itemtype="https://schema.org/Person" style="display:none;">
                        <meta itemprop="name" content="<?php echo esc_attr(get_the_author()); ?>">
                    </div>
                    
                    <!-- Publisher (Organização) -->
                    <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" style="display:none;">
                        <meta itemprop="name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                            <meta itemprop="url" content="<?php echo esc_url(get_site_icon_url()); ?>">
                        </div>
                    </div>
                    
                    <!-- Datas -->
                    <meta itemprop="datePublished" content="<?php echo esc_attr(get_the_date('c')); ?>">
                    <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                    
                    <a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
                        <figure itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                            <?php                         
                                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                $default_image = 'https://agenciaaids.com.br/wp-content/themes/agenciaaids/assets/images/backdrop-ag-aids-compress-web.webp';
                                $image_url = $thumbnail_url ?: $default_image;
                                $image_class = $thumbnail_url ? 'card-image' : 'card-image card-image-default';
                            ?>
                            <img src="<?=esc_url($image_url)?>" alt="<?=esc_attr(get_the_title())?>" class="<?=esc_attr($image_class)?>" loading="lazy" decoding="async" itemprop="url">
                            <meta itemprop="width" content="800">
                            <meta itemprop="height" content="600">
                        </figure>
                        <?php the_title('<h3 itemprop="headline">', '</h3>', true); ?>
                    </a>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                        <?php echo esc_html(get_the_date('d/m/Y')); ?>
                    </time>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
<?php endif;
wp_reset_postdata();
