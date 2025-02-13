<section class="aboutUs" itemscope itemtype="https://schema.org/Organization">
    <span itemprop="name">Nosso time</span>
    <?php the_title('<h1 itemprop="name">', '</h1>'); ?>

    <?php if (have_rows('collaborators_repeater')): // Verifica se há collaborators cadastrados no ACF ?>
        <?php while (have_rows('collaborators_repeater')): the_row(); 
            // Obtendo campos do ACF
            $foto = get_sub_field('foto_collaborator');
            $nome = get_sub_field('nome_collaborator');
            $descricao = get_sub_field('descricao_collaborator');
            $email = get_sub_field('email_collaborator');
            $facebook = get_sub_field('facebook_collaborator');
            $instagram = get_sub_field('instagram_collaborator');
            $linkedin = get_sub_field('linkedin_collaborator');
        ?>

            <article itemscope itemtype="https://schema.org/Person">
                <figure>
                    <img src="<?= esc_url($foto['url']); ?>" alt="<?= esc_attr($nome); ?>" itemprop="image">
                </figure>
                <header>
                    <h2 itemprop="name"><?= esc_html($nome); ?></h2>
                    <p itemprop="description"><?= esc_html($descricao); ?></p>

                    <?php if ($email): ?>
                        <a href="mailto:<?= esc_attr($email); ?>" title="Email de <?= esc_attr($nome); ?>" itemprop="email">
                            <img src="icone-email.svg" alt="ícone de email">
                            <span><?= esc_html($email); ?></span>
                        </a>
                    <?php endif; ?>

                    <h3>Redes Sociais</h3>
                    <ul>
                        <?php 
                            $socials = [
                                'facebook' => 'icone-facebook.svg',
                                'instagram' => 'icone-instagram.svg',
                                'linkedin' => 'icone-linkedin.svg'
                            ];

                            foreach ($socials as $key => $icon) :
                                if (!empty($$key)) : ?>
                                    <li>
                                        <a href="<?= esc_url($$key); ?>" title="<?= ucfirst($key); ?> de <?= esc_attr($nome); ?>" target="_blank" rel="noopener noreferrer" itemprop="sameAs">
                                            <img src="<?= $icon; ?>" alt="Ícone do <?= ucfirst($key); ?>">
                                        </a>
                                    </li>
                                <?php endif;
                            endforeach;
                        ?>
                    </ul>
                </header>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</section>
