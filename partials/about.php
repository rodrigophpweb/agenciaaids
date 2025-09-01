<section class="aboutUs paddingContent" itemscope itemtype="https://schema.org/Organization">
    <span itemprop="name">Nosso time</span>
    <?php the_title('<h1 itemprop="name">', '</h1>'); ?>
    <div class="the_content">
        <p><?php the_content();?></p>
    </div>
    <div class="peoples">
        <?php if (have_rows('collaborators_repeater')): ?>
                <?php while (have_rows('collaborators_repeater')): the_row(); 
                    $foto = get_sub_field('foto_collaborator');
                    $nome = get_sub_field('nome_collaborator');
                    $cargo = get_sub_field('cargo_collaborator');
                    $descricao = get_sub_field('descricao_collaborator');
                    $email = get_sub_field('email_collaborator');
                    $facebook = get_sub_field('facebook_collaborator');
                    $instagram = get_sub_field('instagram_collaborator');
                    $linkedin = get_sub_field('linkedin_collaborator');
                ?>
                    <article itemscope itemtype="https://schema.org/Person">
                        <figure itemprop="image">
                            <?php
                                if (!empty($foto['ID'])) {
                                    echo wp_get_attachment_image($foto['ID'], 'thumbnail', false, [
                                        'alt' => esc_attr($nome),
                                        'itemprop' => 'image'
                                    ]);
                                }
                            ?>
                        </figure>

                        <header>
                            <h2 itemprop="name"><?= esc_html($nome); ?></h2>
                            
                            <?php if ($cargo): ?>
                                <p class="cargo" itemprop="jobTitle"><?= esc_html($cargo); ?></p>
                            <?php endif; ?>

                            <?php if ($descricao): ?>
                                <p itemprop="description"><?= esc_html($descricao); ?></p>
                            <?php endif; ?>

                            <?php if ($email): ?>
                                <div class="containerEmail">
                                    <a href="mailto:<?= esc_attr($email); ?>" title="Email de <?= esc_attr($nome); ?>" itemprop="email">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path d="M64 112c-8.8 0-16 7.2-16 16l0 22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1l0-22.1c0-8.8-7.2-16-16-16L64 112zM48 212.2L48 384c0 8.8 7.2 16 16 16l384 0c8.8 0 16-7.2 16-16l0-171.8L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64l384 0c35.3 0 64 28.7 64 64l0 256c0 35.3-28.7 64-64 64L64 448c-35.3 0-64-28.7-64-64L0 128z" fill="000000"/>
                                        </svg>
                                        <span><?= esc_html($email); ?></span>
                                    </a>
                                </div>
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
                                                    <img src="<?= get_template_directory_uri(); ?>/assets/images/<?= $icon; ?>" alt="Ícone do <?= ucfirst($key); ?>">
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
    </div>
</section>
