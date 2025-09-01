<?php
$args = wp_parse_args($args, [
    'section_id'        => '',
    'class'             => '',
    'title'             => '',
    'subtitle'          => '',
    'content'           => '',
    'post_type'         => 'post',
    'highlight'         => 1,
    'columns'           => 3,
    'itemtype'          => 'https://schema.org/CreativeWork',
    'itemprop_title'    => 'headline',
    'itemprop_excerpt'  => 'description',
    'itemprop_image'    => 'image',
    'itemprop_date'     => 'datePublished',
]);

$query = $args['query'] ?? null;

if (!($query instanceof WP_Query)) {
    $query_args = [
        'post_type'      => $args['post_type'] ?? 'post',
        'posts_per_page' => $args['highlight'] + $args['columns'],
        'paged'          => get_query_var('paged') ?: 1,
    ];
    // Adiciona meta_query ou meta_key/meta_value se existirem nos args
    if (!empty($args['meta_key']) && isset($args['meta_value'])) {
        $query_args['meta_key'] = $args['meta_key'];
        $query_args['meta_value'] = $args['meta_value'];
    }
    // Permite passar outros argumentos extras se necessário
    $extra_args = ['orderby', 'order', 'meta_query', 'tax_query', 'ignore_sticky_posts'];
    foreach ($extra_args as $extra) {
        if (isset($args[$extra])) {
            $query_args[$extra] = $args[$extra];
        }
    }
    $query = new WP_Query($query_args);
}

?>

<section id="<?= esc_attr($args['section_id']) ?>" class="<?= esc_attr($args['class']) ?> paddingContent" aria-labelledby="<?= esc_attr($args['section_id'] . '-title') ?>">
    <?php
        $elements = [
            'title' => [
                'tag' => 'h2',
                'class' => 'section-title',
                'id' => !empty($args['section_id']) ? esc_attr($args['section_id'] . '-title') : null,
                'esc_func' => 'esc_html',
            ],
            'subtitle' => [
                'tag' => 'p',
                'class' => 'section-subtitle',
                'esc_func' => 'esc_html',
            ],
            'content' => [
                'tag' => 'p',
                'class' => 'section-content',
                'esc_func' => 'esc_html',
            ],
        ];

        foreach ($elements as $key => $config) {
            if (!empty($args[$key])) {
                $tag = $config['tag'];
                $class = $config['class'];
                $esc_func = $config['esc_func'];
                $id = isset($config['id']) ? " id=\"{$config['id']}\"" : '';

                echo "<{$tag}{$id} class=\"{$class}\">" . $esc_func($args[$key]) . "</{$tag}>";
            }
        }
        
        if ($query && $query->have_posts()): 
    ?>
            <div class="post-grid-two-columns">
                <?php
                    $counter = 0;
                    while ($query->have_posts()): $query->the_post();

                        $video_url = get_field('embed', get_the_ID());
                        $thumb_url = '';

                        // Definir tamanho conforme posição
                        $thumb_size = ($counter === 0) ? 'large' : 'thumbnail';

                        if ($video_url && function_exists('get_youtube_thumbnail')) {
                            $thumb_url = get_youtube_thumbnail($video_url);
                        }

                        if (empty($thumb_url) && has_post_thumbnail()) {
                            $thumb_url = get_the_post_thumbnail_url(get_the_ID(), $thumb_size);
                        }
                        // Fallback para imagem padrão se não houver thumbnail
                        if (empty($thumb_url)) {
                            $thumb_url = get_template_directory_uri() . '/assets/images/backdrop-ag-aids-compress-web.webp';
                        }
                ?>

                        <?php if ($counter === 0): ?>
                            <article class="featured-post" itemscope itemtype="<?= esc_attr($args['itemtype']) ?>">
                                <a href="<?= esc_url(get_permalink()) ?>" itemprop="url">
                                    <figure itemprop="<?= esc_attr($args['itemprop_image']) ?>" itemscope itemtype="https://schema.org/ImageObject">
                                        <img src="<?= esc_url($thumb_url) ?>" alt="<?= esc_attr(get_the_title()) ?>" itemprop="url" loading="lazy">
                                    </figure>

                                    <div class="post-info">
                                        <h3 itemprop="<?= esc_attr($args['itemprop_title']) ?>"><?= esc_html(get_the_title()) ?></h3>                                        
                                        <p itemprop="<?= esc_attr($args['itemprop_excerpt']) ?>"><?= esc_html(wp_trim_words(get_the_excerpt(), 20, '...')) ?></p>
                                        <time datetime="<?= get_the_date('c') ?>" itemprop="<?= esc_attr($args['itemprop_date']) ?>">
                                            <?= get_the_date('d \d\e F \d\e Y') ?>
                                        </time>
                                        <meta itemprop="dateModified" content="<?= get_the_modified_date('c') ?>" />
                                        <meta itemprop="author" content="<?= get_the_author() ?>" />
                                    </div>
                                </a>
                            </article>

                            <div class="secondary-posts">
                        <?php else: ?>
                            <article class="post-card" role="listitem" itemscope itemtype="<?= esc_attr($args['itemtype']) ?>">
                                <a href="<?= esc_url(get_permalink()) ?>" itemprop="url">
                                    <figure itemprop="<?= esc_attr($args['itemprop_image']) ?>" itemscope itemtype="https://schema.org/ImageObject">
                                        <img src="<?= esc_url($thumb_url) ?>" alt="<?= esc_attr(get_the_title()) ?>" itemprop="url" loading="lazy">
                                    </figure>

                                    <div class="post-info">
                                        <h3 itemprop="<?= esc_attr($args['itemprop_title']) ?>"><?= esc_html(get_the_title()) ?></h3>
                                        
                                        <p itemprop="<?= esc_attr($args['itemprop_excerpt']) ?>"><?= esc_html(get_the_excerpt()) ?></p>
                                        <time datetime="<?= get_the_date('c') ?>" itemprop="<?= esc_attr($args['itemprop_date']) ?>">
                                            <?= get_the_date('d \d\e F \d\e Y') ?>
                                        </time>
                                        <meta itemprop="dateModified" content="<?= get_the_modified_date('c') ?>" />
                                        <meta itemprop="author" content="<?= get_the_author() ?>" />
                                    </div>
                                </a>
                            </article>
                        <?php endif; ?>
                    <?php 
                    $counter++; endwhile; wp_reset_postdata(); 
                ?>
                </div> <!-- .secondary-posts -->
            </div> <!-- .post-grid-two-columns -->
        <?php endif; 
    ?>
</section>