<?php
// Protege acesso direto
if ( ! defined('ABSPATH') ) {
    exit;
}

// Garante que $args existe
$args = isset($args) && is_array($args) ? $args : [];

$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
?>
        <section class="highlight <?= esc_attr($args['class'] ?? ''); ?> paddingContent" itemscope itemtype="http://schema.org/Article">
            <article>
                <span class="category" itemprop="articleSection">Destaque</span>
                <?php the_title('<h1 itemprop="headline">', '</h1>'); ?>
                <div itemprop="description">
                    <?php the_excerpt(); ?>
                </div>
                <time datetime="<?= esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                    <?= esc_html(get_the_date('d \d\e F \d\e Y')); ?>
                </time>
            </article>
            <figure itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('posts_highlight', [
                        'alt' => get_the_title(),
                        'itemprop' => 'url',
                        'loading' => 'lazy'
                    ]);
                }
                ?>
            </figure>
        </section>
<?php
    endwhile;
    wp_reset_postdata();
else :
?>
    <p>Nenhum destaque encontrado.</p>
<?php endif;