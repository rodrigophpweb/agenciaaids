<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<section class="lectures paddingContent" itemscope itemtype="https://schema.org/Organization">
    <h1 itemprop="name">Palestras</h1>
    <h2 itemprop="description">Conheça nossa equipe dedicada e apaixonada, composta por profissionais talentosos.</h2>

    <div class="speakers">
        <?php
        $palestrantes = new WP_Query([
            'post_type'      => 'palestrantes',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
            'no_found_rows'  => true,
        ]);

        if ($palestrantes->have_posts()) :
            while ($palestrantes->have_posts()) : $palestrantes->the_post();
                $email  = get_field('email_palestrante');   // ACF
                $cidade = get_field('cidade_palestrante');  // ACF
                $title  = get_the_title();
                $img_id = get_post_thumbnail_id();
                
                // Sanitize ACF fields
                $email  = $email ? sanitize_email($email) : '';
                $cidade = $cidade ? sanitize_text_field($cidade) : '';
                ?>
                <article class="speaker-card" itemscope itemtype="https://schema.org/Person">
                    <figure itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                        <?php if ($img_id) : ?>
                            <?php echo wp_get_attachment_image($img_id, 'medium', false, [
                                'alt'      => esc_attr($title),
                                'title'    => esc_attr($title),
                                'itemprop' => 'url',
                                'loading'  => 'lazy',
                                'width'    => 300,
                                'height'   => 300,
                            ]); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/default-avatar.png'); ?>"
                                alt="<?php echo esc_attr__('Foto não disponível', 'agenciaaids'); ?>"
                                title="<?php echo esc_attr__('Sem imagem', 'agenciaaids'); ?>"
                                width="300" 
                                height="300"
                                loading="lazy"
                                itemprop="url">
                        <?php endif; ?>
                    </figure>

                    <h3 itemprop="name"><?php echo esc_html($title); ?></h3>

                    <?php if ($cidade) : ?>
                        <span class="city" itemprop="address"><?php echo esc_html($cidade); ?></span>
                    <?php endif; ?>     
                    
                    <?php if (get_the_content()) : ?>
                        <div itemprop="description">
                            <?php the_content(); ?>
                        </div>
                    <?php elseif (get_the_excerpt()) : ?>
                        <p itemprop="description"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>

                    <?php if ($email && is_email($email)) : ?>
                        <a href="<?php echo esc_url('mailto:' . $email); ?>"
                           title="<?php echo esc_attr(sprintf(__('Enviar e-mail para %s', 'agenciaaids'), $title)); ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           itemprop="email"><?php echo esc_html($email); ?></a>
                    <?php endif; ?>
                </article>

            <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>' . esc_html__('Nenhum palestrante encontrado.', 'agenciaaids') . '</p>';
        endif;
        ?>
    </div>
</section>
