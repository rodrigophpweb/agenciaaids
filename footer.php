<footer class="paddingContent" itemscope itemtype="https://schema.org/Organization">
    <meta itemprop="name" content="Agência de notícias da Aids">
    
    <nav class="menu" itemprop="hasPart" itemscope itemtype="https://schema.org/SiteNavigationElement">
        <?php
            wp_nav_menu(
                [
                    'theme_location'    => 'footer-menu',
                    'container'         => 'div',
                    'container_class'   => 'footer-menu-container',
                    'menu_class'        => 'footer-menu',
                    'fallback_cb'       => false
                ]
            );
        ?>
    </nav>

    <section class="socialMediaAddress">
        <ul class="mnuSocialMediaFooter">
            <?php if( have_rows('socialmedia', 'option') ): ?>
                <?php while( have_rows('socialmedia', 'option') ): the_row(); 
                    $link = get_sub_field('link', 'option');
                    $icon = get_sub_field('icon', 'option');
                ?>
                    <li>
                        <a href="<?=esc_url($link)?>" target="_blank" rel="noopener noreferrer nofollow" title="Visite nossa página" itemprop="sameAs">
                            <img src="<?=esc_url($icon['url'])?>" alt="<?=esc_attr($icon['alt'])?>">
                        </a>
                    </li>
                <?php endwhile; ?>
            <?php endif; ?>
        </ul>

        <div class="addressPhoneMail">
            <address itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                <a href="<?=esc_url(get_field('googleMapsUrl', 'option'))?>" title="Nossa Localização" rel="noopener noreferrer" target="_blank">
                    <span itemprop="streetAddress"><?=esc_html(get_field('ourAddress', 'option'))?></span>
                </a>
            </address>
            <div class="contactsPhoneMail">
                <?php
                    $phone1 = get_field('phone1', 'option');
                    $phone2 = get_field('phone2', 'option');
                    $formatted_phone1 = '55' . preg_replace('/\D+/', '', $phone1);
                    $formatted_phone2 = '55' . preg_replace('/\D+/', '', $phone2);
                    $email = get_field('ourMail', 'option');
                ?>
                <a href="tel:<?=esc_attr($formatted_phone1)?>" title="Ligue para nós" rel="noopener noreferrer">
                    <span itemprop="telephone">+55 <?=esc_html($phone1)?></span>
                </a> <span class="pipe">|</span> 
                <a href="tel:<?=esc_attr($formatted_phone2)?>" title="Ligue para nós" rel="noopener noreferrer">
                    <span itemprop="telephone"><?=esc_html($phone2)?></span>
                </a> <span class="pipe">|</span>     
                <a href="mailto:<?=esc_attr($email)?>" target="_blank" rel="noopener noreferrer">
                    <span itemprop="email"><?=esc_html($email)?></span>
                </a>
            </div>
        </div>            
    </section>

    <div class="copyrigth">
        <small>&copy;<time datetime="<?=date('Y')?>"><?=date('Y')?></time> - Todos os direitos reservados - <span itemprop="name">Agência de notícias da Aids</span></small>
    </div>
</footer>
