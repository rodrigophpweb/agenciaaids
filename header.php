<!DOCTYPE html>
<html <?php language_attributes()?>>
<head>
    <meta charset="<?=bloginfo('charset')?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php wp_head()?>
</head>
<body <?php body_class( 'class-name' ); ?>>
    <?php wp_body_open(); ?>
    <header itemscope itemtype="https://schema.org/WPHeader">
        <nav class="socialMediaAcessibility" itemscope itemtype="https://schema.org/SiteNavigationElement">
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
                    <button class="btnContrast"></button>
                    <ul class="mnuContrast">
                        <li>Contraste aumentado</li>
                        <li>Monocromático</li>
                        <li>Escala de cinza invertida</li>
                        <li>Cor invertida</li>
                        <li>Cores originais</li>
                    </ul>
                </div>
                <button class="sizeButtonMore">A+</button>
                <button class="sizeButtonLess">A-</button>
            </div>
        </nav>

        <div class="brandAds">
            <figure class="brand" itemscope itemtype="https://schema.org/Organization">
                <?php
                    echo (function_exists('the_custom_logo') && has_custom_logo()) 
                        ? get_custom_logo() 
                        : '<a href="' . esc_url(home_url('/')) . '" itemprop="url"><span itemprop="name">' . get_bloginfo('name') . '</span></a>';
                ?>
            </figure>

            <div class="ads" itemscope itemtype="https://schema.org/WPAdBlock">
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
        </div>

        <nav class="mnuDefault" itemscope itemtype="https://schema.org/SiteNavigationElement">
            <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'header-menu',
                        'container' => '',
                        'menu_class' => 'mnuPrincipal',
                        'fallback_cb' => false
                    )
                );
            ?>               
        </nav>
    </header>

<main>