<section class="lectures paddingContent" itemscope itemtype="https://schema.org/Organization">
    <h1 itemprop="name">Palestras</h1>
    <h2 itemprop="description">Conheça nossa equipe dedicada e apaixonada, composta por profissionais talentosos.</h2>

    <div class="speakers">
        <?php
        $palestrantes = new WP_Query([
            'post_type'      => 'palestrantes',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        if ($palestrantes->have_posts()) :
            while ($palestrantes->have_posts()) : $palestrantes->the_post();
                $email  = get_field('email_palestrante');   // ACF
                $cidade = get_field('cidade_palestrante');  // ACF
                $title  = get_the_title();
                $img_id = get_post_thumbnail_id();
                ?>
                <article class="speaker-card" itemscope itemtype="https://schema.org/Person">
                    <figure itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                        <?php if ($img_id) : ?>
                            <?= wp_get_attachment_image($img_id, 'medium', false, [
                                'alt'     => esc_attr($title),
                                'title'   => esc_attr($title),
                                'itemprop'=> 'url',
                                'loading' => 'lazy',
                                'width'   => 300,
                                'height'  => 300,
                            ]); ?>
                        <?php else : ?>
                            <img src="<?= esc_url(get_template_directory_uri() . '/assets/img/default-avatar.png'); ?>"
                                alt="Foto não disponível"
                                title="Sem imagem"
                                width="300" height="300"
                                loading="lazy"
                                itemprop="url">
                        <?php endif; ?>
                    </figure>

                    <h3 itemprop="name"><?= esc_html($title); ?></h3>

                    <?php if ($cidade) : ?>
                        <span class="city" itemprop="address"><?= esc_html($cidade); ?></span>
                    <?php endif; ?>     
                    
                    <?php if (get_the_content()) : ?>
                        <div itemprop="description">
                            <?= apply_filters('the_content', get_the_content()); ?>
                        </div>
                    <?php elseif (get_the_excerpt()) : ?>
                        <p itemprop="description"><?= esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>

                    <?php if ($email) : ?>
                        <a href="mailto:<?= esc_attr($email); ?>"
                        title="Enviar e-mail para <?= esc_attr($title); ?>"
                        target="_blank"
                        itemprop="email"><?= esc_html($email); ?></a>
                    <?php endif; ?>
                </article>

            <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>Nenhum palestrante encontrado.</p>';
        endif;
        ?>
    </div>
</section>
