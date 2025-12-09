<?php 
    get_header();
    get_template_part('partials/breadcrumb');
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/Article">
        <header class="entry-header">
            <?php 
                ($category = get_the_category()[0] ?? null) ? 
                printf('<span class="post-category" itemprop="articleSection">%s</span>', esc_html($category->name)) : ''; 
            ?>

            <h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
            
            <p itemprop="description"><?php the_excerpt(); ?></p>
            
            <?php
                // Obtém a data e hora da publicação e última atualização
                $published = get_the_date('Y-m-d\TH:i:sP'); // Formato ISO 8601
                $published_human = get_the_date('d/m/Y \à\s H\hi'); // Formato legível

                $updated = get_the_modified_date('Y-m-d\TH:i:sP');
                $updated_human = get_the_modified_date('d/m/Y \à\s H\hi');

                // Calcula tempo estimado de leitura (assumindo média de 200 palavras por minuto)
                $word_count = str_word_count(strip_tags(get_post_field('post_content', get_the_ID())));
                $reading_time = ceil($word_count / 200);
            ?>

            <div class="post-meta">
                <span>
                    <time datetime="<?= esc_attr($published); ?>" itemprop="datePublished"><?= esc_html($published_human); ?></time>
                </span>
                
                <?php if ($published !== $updated): ?>
                    • Atualizado em <time datetime="<?= esc_attr($updated); ?>" itemprop="dateModified"><?= esc_html($updated_human); ?></time>
                <?php endif; ?>
                
                • <span itemprop="timeRequired" content="PT<?= esc_attr($reading_time); ?>M"><?= esc_html($reading_time); ?> min de leitura</span>
            </div>
        </header>

        <?php 
            // Player de Text-to-Speech
            get_template_part('partials/tts-player'); 
        ?>
        
        <?php if (has_post_thumbnail()): ?>
            <figure class="post-thumbnail">
                <?php the_post_thumbnail('full', ['itemprop' => 'image', 'fetchpriority' => 'high']); ?>
            </figure>
        <?php endif; ?>

        <div class="entry-content" itemprop="articleBody">
            <?php the_content(); ?>
        </div>        
    </article>
<?php 
    get_template_part('', 'related-news');
    get_footer();