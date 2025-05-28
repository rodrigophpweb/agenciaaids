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

if (empty($args['query'])) {
    $query_args = [
        'post_type'      => $args['post_type'],
        'posts_per_page' => $args['highlight'] + $args['columns'],
    ];
    $query = new WP_Query($query_args);
} else {
    $query = $args['query'];
}
?>

<section id="<?= esc_attr($args['section_id']) ?>" class="<?= esc_attr($args['class']) ?> paddingContent" aria-labelledby="<?= esc_attr($args['section_id'] . '-title') ?>">
    <?php if (!empty($args['title'])): ?>
        <h2 id="<?= esc_attr($args['section_id'] . '-title') ?>" class="section-title"><?= esc_html($args['title']) ?></h2>
    <?php endif; ?>

    <?php if (!empty($args['subtitle'])): ?>
        <p class="section-subtitle"><?= esc_html($args['subtitle']) ?></p>
    <?php endif; ?>

    <?php if (!empty($args['content'])): ?>
        <p class="section-content"><?= esc_html($args['content']) ?></p>
    <?php endif; ?>

    <?php if ($query && $query->have_posts()): ?>
        <div class="post-grid-two-columns">
            <?php
            $counter = 0;
            while ($query->have_posts()): $query->the_post();

                $video_url = get_field('embed', get_the_ID());
                $thumb_url = '';

                if ($video_url && function_exists('get_youtube_thumbnail')) {
                    $thumb_url = get_youtube_thumbnail($video_url);
                }

                if (empty($thumb_url) && has_post_thumbnail()) {
                    $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
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
                                <p itemprop="<?= esc_attr($args['itemprop_excerpt']) ?>"><?= esc_html(get_the_excerpt()) ?></p>
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
            <?php $counter++; endwhile; wp_reset_postdata(); ?>
            <?php echo '<pre>' . get_youtube_thumbnail('https://www.youtube.com/embed/CEZAJTkMZfk?si=L1q9cKegkFOjNbrs') . '</pre>'; ?>

            </div> <!-- .secondary-posts -->
        </div> <!-- .post-grid-two-columns -->
    <?php endif; ?>
</section>
