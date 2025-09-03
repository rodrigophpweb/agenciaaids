<!DOCTYPE html>
<html <?php language_attributes()?>>
<head>
    <meta charset="<?=bloginfo('charset')?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
            if (is_front_page()) {
                bloginfo('name');
                echo ' | ';
                bloginfo('description');
            } else {
                wp_title('|', true, 'right');
                bloginfo('name');
            }
        ?>
    </title>
    <?php wp_head()?>
</head>
<body <?php body_class( 'class-name' ); ?>>
    <?php wp_body_open(); ?>
    <header itemscope itemtype="https://schema.org/WPHeader">
        <nav class="socialMediaAcessibility paddingContent" itemscope itemtype="https://schema.org/SiteNavigationElement">
            <ul class="mnuSocialMedia">
                <?php if( have_rows('socialmedia', 'option') ): ?>
                    <?php while( have_rows('socialmedia', 'option') ): the_row(); 
                        $link = get_sub_field('link', 'option');
                        $icon = get_sub_field('icon', 'option');
                    ?>
                        <li>
                            <a href="<?=esc_url($link)?>" target="_blank" rel="noopener noreferrer nofollow">
                                <img src="<?=esc_url($icon['url'])?>" alt="<?=esc_attr($icon['alt'])?>">
                            </a>
                        </li>
                    <?php endwhile; ?>
                <?php endif; ?>
            </ul>

            <div class="acessibility">
                <strong>Acessibilidade</strong>
                <div class="contrast">
                    <button class="btnContrast">                    
                        <svg width="20px" height="20px" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#000000" fill-rule="evenodd" d="M68,121 C72.9705627,121 77,116.970563 77,112 C77,107.029437 72.9705627,103 68,103 C63.0294373,103 59,107.029437 59,112 C59,116.970563 63.0294373,121 68,121 Z M68,119.8 C72.2313475,119.8 75.8,116.307821 75.8,112 C75.8,108.076069 72.6281738,104.2 68,104.2 L68,119.8 Z" transform="matrix(-1 0 0 1 77 -103)"/>
                        </svg>
                    </button>
                    <ul class="mnuContrast">
                        <li>Contraste aumentado</li>
                        <li>Monocromático</li>
                        <li>Escala de cinza invertida</li>
                        <li>Cor invertida</li>
                        <li>Cores originais</li>
                    </ul>
                </div>
                <button class="sizeButtonMore">                    
                    <svg width="20px" height="20px" viewBox="0 0 76 76" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" baseProfile="full" enable-background="new 0 0 76.00 76.00" xml:space="preserve">
                        <path fill="#000000" fill-opacity="1" stroke-width="0.2" stroke-linejoin="round" d="M 45.0001,25L 50,25L 50,20L 54,20L 54,25L 59.0001,25L 59.0001,29L 54,29L 54,34L 50,34L 50,29L 45.0001,29L 45.0001,25 Z M 52.1429,56L 45.4571,56L 42.283,46.7429L 28.4375,46.7429L 25.4,56L 18.7143,56L 32.1339,20L 38.8277,20L 52.1429,56 Z M 40.7402,42.1143L 35.8464,27.417C 35.7018,26.9455 35.5464,26.1875 35.3804,25.1429L 35.2759,25.1429C 35.1313,26.1018 34.9679,26.8598 34.7857,27.417L 29.9563,42.1143L 40.7402,42.1143 Z "/>
                    </svg>
                </button>
                <button class="sizeButtonLess">                    
                    <svg width="20px" height="20px" viewBox="0 0 76 76" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" baseProfile="full" enable-background="new 0 0 76.00 76.00" xml:space="preserve">
                        <path fill="#000000" fill-opacity="1" stroke-linejoin="round" d="M 45,25L 59,25L 59,29L 45,29L 45,25 Z M 52.1429,56L 45.4571,56L 42.283,46.7429L 28.4375,46.7429L 25.4,56L 18.7143,56L 32.1339,20L 38.8277,20L 52.1429,56 Z M 40.7402,42.1143L 35.8464,27.417C 35.7018,26.9455 35.5464,26.1875 35.3804,25.1429L 35.2759,25.1429C 35.1313,26.1018 34.9679,26.8598 34.7857,27.417L 29.9563,42.1143L 40.7402,42.1143 Z "/>
                    </svg>
                </button>
            </div>
        </nav>

        <div class="brandAds paddingContent">
            <figure class="brand" itemscope itemtype="https://schema.org/Organization">
                <?php
                    echo (function_exists('the_custom_logo') && has_custom_logo()) 
                        ? get_custom_logo() 
                        : '<a href="' . esc_url(home_url('/')) . '" itemprop="url"><span itemprop="name">' . get_bloginfo('name') . '</span></a>';
                ?>
            </figure>

            <div class="ads" itemscope itemtype="https://schema.org/WPAdBlock">
                
                <?php if (is_singular('noticias') && in_array(get_the_ID(), [105690, 105688, 102609])) :?>
                    <a href="https://www.sescsp.org.br/bienal-sesc-de-danca/?ads=agenciaaids" target="_blank" itemprop="url">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ads/banner-bienal-danca-jul25-sesc.gif" alt="Banner - Bienal SESC de Dança" >
                    </a>
                <?php else:?>
                
                    <a href="https://www.sescsp.org.br/projetos/oju-roda-sesc-de-cinemas-negros/?ads=agenciaaids" target="_blank" itemprop="url">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ads/sesc-sao-paulo-oju-roda-sesc-de-cinemas-negros.gif" alt="Banner - OJU - Roda Sesc de Cinemas Negros" >
                    </a>
                <?php endif; ?>
                
                <?php
                    $ads_query = new WP_Query(array(
                        'post_type'         => 'ads',
                        'posts_per_page'    => 1,
                        'orderby'           => 'rand'
                    ));
                    if ($ads_query->have_posts()) :
                        while ($ads_query->have_posts()) : $ads_query->the_post();
                            if ($ad_image = get_the_post_thumbnail_url(get_the_ID(), 'full')) :
                ?>
                                <a href="<?php the_permalink(); ?>" itemprop="url">
                                    <img src="<?= esc_url($ad_image); ?>" alt="<?php the_title_attribute(); ?>" itemprop="image">
                                </a>
                <?php
                            endif;
                        endwhile;
                        wp_reset_postdata();
                    endif;
                ?>
            </div>
            <button class="btnMobile">☰</button>
        </div>

        <nav class="mnuDefault paddingContent" itemscope itemtype="https://schema.org/SiteNavigationElement">
            <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'header-menu',
                        'container' => '',
                        'menu_class' => 'mnuPrincipal',
                        'fallback_cb' => false
                    )
                );
                get_template_part('partials/searchform');
            ?>               
        </nav>
    </header>

<main itemscope itemtype="https://schema.org/WebPage">