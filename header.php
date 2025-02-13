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
    <header>
        <nav class="socialMediaAcessibility">
            <ul class="mnuSocialMedia">
                <li>
                    <a href="https://www.facebook.com/agenciaaids/" title="Agência AIDS no facebook" target="_blank" rel="noopener noreferrer nofollow">

                    </a>
                </li>
                <li>
                    <a href="https://x.com/agenciaaids" title="Agência AIDS no X" target="_blank" rel="noopener noreferrer nofollow">
                </li>
                <li>
                    <a href="https://www.instagram.com/agenciaaids/" title="Agência AIDS no Instagram" target="_blank" rel="noopener noreferrer nofollow">
                </li>
                <li>
                    <a href="https://www.youtube.com/channel/UCqBcEcgx8QaU--YfJHojVfg" title="Agência AIDS no Youtube" target="_blank" rel="noopener noreferrer nofollow">
                </li>
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
            <figure class="brand">
                <?php
                    if (function_exists('the_custom_logo') && has_custom_logo()) {
                        echo get_custom_logo(); // Exibe o logotipo personalizado
                    } else {
                        echo '<a href="' . esc_url(home_url('/')) . '">' . get_bloginfo('name') . '</a>'; // Exibe o nome do site como fallback
                    }
                ?>
            </figure>

            <div class="ads">
                <img src="" alt="">
            </div>
        </div>
    </header>

<main>