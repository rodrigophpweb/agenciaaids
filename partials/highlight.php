<?php
// Usa os argumentos diretamente para criar a consulta
$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        ?>
        <section class="highlight <?=$args['class'] ?? ''?> paddingContent">
            <article>
                <span class="category">Destaque</span>
                <?php the_title('<h1>', '</h1>'); ?>
                <?php the_excerpt(); ?>
                <time datetime="<?= get_the_date('Y-m-d'); ?>"><?= get_the_date('d \d\e F \d\e Y'); ?></time>
            </article>
            <figure>
                <?php the_post_thumbnail('posts_highlight', ['alt' => get_the_title(), 'itemprop' => 'image']); ?>
            </figure>
        </section>
        <?php
    endwhile;
    wp_reset_postdata();
else :
    // Caso nenhum post seja encontrado
    ?>
    <p>Nenhum destaque encontrado.</p>
    <?php
endif;
?>