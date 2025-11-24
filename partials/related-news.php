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

if ($related_posts && $related_posts->have_posts()) : ?>
    <section class="related-posts paddingContent" aria-labelledby="related-posts-title">
        <h2 id="related-posts-title">Artigos Relacionados</h2>
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
                    
                    <a href="<?php the_permalink(); ?>" rel="bookmark">
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
                        <?php the_title('<h3 itemprop="headline">', '</h3>')?>
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
?>
<style>
    /* Related Posts Section */
    .related-posts {
        width: 100%;
        margin: 3rem 0;
        padding: 2rem 0;
        background-color: var(--background-secondary, #f8f9fa);
        
        /* Título da seção */
        #related-posts-title {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 700;
            color: var(--buttonBlue);
            margin-bottom: 2rem;
            text-align: center;
            line-height: 1.3;
            
            /* Acessibilidade - foco visível */
            &:focus-visible {
                outline: 3px solid var(--color-focus, #0066cc);
                outline-offset: 4px;
                border-radius: 4px;
            }
        }
        
        /* Grid de artigos relacionados */
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            
            /* Responsividade para tablets */
            @media (min-width: 768px) and (max-width: 1024px) {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
            
            /* Responsividade para mobile */
            @media (max-width: 767px) {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            /* Card de artigo relacionado */
            .related-card {
                display: flex;
                flex-direction: column;
                background-color: var(--card-background, #ffffff);
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                position: relative;
                
                /* Acessibilidade - prefers-reduced-motion */
                @media (prefers-reduced-motion: reduce) {
                    transition: none;
                }
                
                /* Hover - elevação do card */
                &:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
                }
                
                /* Focus within - quando qualquer elemento interno recebe foco */
                &:focus-within {
                    outline: 3px solid var(--color-focus, #0066cc);
                    outline-offset: 2px;
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
                }
                
                /* Link principal do card */
                a {
                    display: block;
                    text-decoration: none;
                    color: inherit;
                    flex: 1;
                    
                    /* Remove outline padrão, pois o card já tem foco visível */
                    &:focus {
                        outline: none;
                    }
                    
                    /* Indicador de foco via teclado */
                    &:focus-visible {
                        outline: 2px solid var(--color-focus, #0066cc);
                        outline-offset: -2px;
                        border-radius: 12px 12px 0 0;
                    }
                    
                    /* Hover no link */
                    &:hover {
                        figure img {
                            transform: scale(1.05);
                        }
                        
                        h3 {
                            color: var(--color-primary, #d32f2f);
                        }
                    }
                    
                    /* Active - feedback de clique */
                    &:active {
                        figure img {
                            transform: scale(0.98);
                        }
                    }
                    
                    /* Figure da imagem */
                    figure {
                        position: relative;
                        margin: 0;
                        padding: 0;
                        overflow: hidden;
                        aspect-ratio: 16 / 9;
                        background-color: var(--image-placeholder, #e0e0e0);
                        
                        /* Imagem do artigo */
                        img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            object-position: center;
                            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                            display: block;
                            
                            /* Acessibilidade - prefers-reduced-motion */
                            @media (prefers-reduced-motion: reduce) {
                                transition: none;
                            }
                            
                            /* Fallback quando imagem não carrega */
                            &[alt]:not([src]) {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                background-color: var(--image-placeholder, #e0e0e0);
                                color: var(--text-secondary, #666);
                                font-size: 0.875rem;
                                text-align: center;
                                padding: 1rem;
                            }
                            
                            /* Imagem padrão (fallback) */
                            &.card-image-default {
                                opacity: 0.6;
                                filter: grayscale(20%);
                            }
                        }
                        
                        /* Overlay sutil no hover */
                        &::after {
                            content: '';
                            position: absolute;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            background: linear-gradient(to top, rgba(0, 0, 0, 0.3) 0%, transparent 50%);
                            opacity: 0;
                            transition: opacity 0.3s ease;
                            pointer-events: none;
                        }
                        
                        &:hover::after {
                            opacity: 1;
                        }
                    }
                    
                    /* Título do artigo */
                    h3 {
                        font-size: clamp(1rem, 2vw, 1.25rem);
                        font-weight: 600;
                        line-height: 1.4;
                        color: var(--text-primary, #1a1a1a);
                        margin: 0;
                        padding: 1rem 1.25rem 0.75rem;
                        transition: color 0.2s ease;
                        
                        /* Limitação de linhas com ellipsis */
                        display: -webkit-box;
                        -webkit-box-orient: vertical;
                        -webkit-line-clamp: 3;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        
                        /* Acessibilidade - prefers-reduced-motion */
                        @media (prefers-reduced-motion: reduce) {
                            transition: none;
                        }
                    }
                }
                
                /* Data de publicação */
                time {
                    display: block;
                    font-size: 0.875rem;
                    color: var(--text-secondary, #666);
                    padding: 0 1.25rem 1.25rem;
                    margin-top: auto;
                    font-weight: 400;
                    
                    /* Ícone de calendário antes da data */
                    &::before {
                        content: '📅';
                        margin-right: 0.5rem;
                        font-size: 1rem;
                        vertical-align: middle;
                    }
                    
                    /* Acessibilidade - foco visível */
                    &:focus-visible {
                        outline: 2px solid var(--color-focus, #0066cc);
                        outline-offset: 2px;
                        border-radius: 4px;
                    }
                }
            }
        }
        
        /* Modo escuro (dark mode) */
        @media (prefers-color-scheme: dark) {
            background-color: var(--background-secondary-dark, #1a1a1a);
            
            #related-posts-title {
                color: var(--text-primary-dark, #f5f5f5);
            }
            
            .related-grid .related-card {
                background-color: var(--card-background-dark, #2a2a2a);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
                
                &:hover {
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
                }
                
                a h3 {
                    color: var(--text-primary-dark, #f5f5f5);
                }
                
                time {
                    color: var(--text-secondary-dark, #b0b0b0);
                }
            }
        }
        
        /* Alto contraste para acessibilidade */
        @media (prefers-contrast: high) {
            .related-grid .related-card {
                border: 2px solid var(--border-high-contrast, #000);
                
                a:focus-visible {
                    outline-width: 3px;
                }
                
                &:focus-within {
                    outline-width: 4px;
                }
            }
        }
        
        /* Animação de entrada dos cards */
        @media (prefers-reduced-motion: no-preference) {
            .related-grid .related-card {
                animation: fadeInUp 0.5s ease-out backwards;
                
                &:nth-child(1) { animation-delay: 0.1s; }
                &:nth-child(2) { animation-delay: 0.2s; }
                &:nth-child(3) { animation-delay: 0.3s; }
            }
        }
    }
    
    /* Keyframes para animação de entrada */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Acessibilidade - foco visível global */
    *:focus-visible {
        outline: 2px solid var(--color-focus, #0066cc);
        outline-offset: 2px;
    }
    
    /* Melhorias para leitores de tela */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }
</style>
