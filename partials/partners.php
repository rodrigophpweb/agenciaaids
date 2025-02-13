<section class="support" itemscope itemtype="https://schema.org/Organization">
    <h2 itemprop="name">Apoio</h2>
    
    <?php if (have_rows('support_repeater')): // Verifica se o Repeater tem dados ?>
        <figure class="logoInSVG" itemprop="sponsor">
            <?php while (have_rows('support_repeater')): the_row(); 
                $logo = get_sub_field('support_logo'); // Campo de imagem do ACF
                $link = get_sub_field('support_link'); // Campo de link do ACF
                $alt_text = get_sub_field('alt_text'); // Campo opcional para texto alternativo
            ?>
                <a href="<?= esc_url($link); ?>" target="_blank" rel="noopener noreferrer" itemprop="url">
                    <img src="<?= esc_url($logo['url']); ?>" alt="<?= esc_attr($alt_text); ?>" itemprop="logo">
                </a>
            <?php endwhile; ?>
        </figure>
    <?php endif; ?>
</section>
