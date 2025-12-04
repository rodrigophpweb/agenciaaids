<section class="support paddingContent" itemscope itemtype="https://schema.org/Organization">
    <h2 itemprop="name"><?php echo esc_html(get_field('title_sponsor','option')); ?></h2>
    
    <?php if (have_rows('support_repeater','option')): ?>
        <figure class="logoInSVG" itemprop="sponsor">
            <?php while (have_rows('support_repeater','option')): the_row(); 
                $logo = get_sub_field('support_logo','option'); 
                $link = get_sub_field('support_link','option'); 
            ?>
                <a href="<?=esc_url($link['url'])?>?utm_source=agenciaaids&utm_medium=logotipo-parceiro&utm_campaign=sessao-de-parceiros-da-agencia-aids" target="_blank" rel="noopener noreferrer" itemprop="url" title="<?=esc_attr($link['title'])?>">
                    <img src="<?= esc_url($logo['url']); ?>" alt="<?=esc_attr($logo['alt'])?>" itemprop="logo"></a>
            <?php endwhile; ?>
        </figure>
    <?php endif; ?>
</section>
