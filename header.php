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
        <div class="socialMediaAcessibility">
            <nav class="socialMedia">
                <ul>
                    <li>Facebook</li>
                    <li>X</li>
                    <li>Instagram</li>
                    <li>Youtube</li>
                </ul>
            </nav>

            <nav class="acessibility">
                <strong>Acessibilidade</strong>
                <div class="contrast">
                    <button class="btnContrast"></button>
                    <ul>
                        <li>Contraste aumentado</li>
                        <li>Monocromático</li>
                        <li>Escala de cinza invertida</li>
                        <li>Cor invertida</li>
                        <li>Cores originais</li>
                    </ul>
                </div>
                <button>A+</button>
                <button>A-</button>
            </nav>
        </div>

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