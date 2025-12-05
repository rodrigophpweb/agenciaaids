<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<section class="doubt paddingContent" itemscope itemtype="https://schema.org/FAQPage">
    <article class="info">
        <?php 
        $doubt_image = get_field('doubt_image', 'option');
        if ($doubt_image && is_array($doubt_image)) :
            $image_url = isset($doubt_image['url']) ? esc_url($doubt_image['url']) : '';
            $image_alt = isset($doubt_image['alt']) ? esc_attr($doubt_image['alt']) : esc_attr__('Imagem de dúvidas', 'agenciaaids');
        ?>
            <img src="<?php echo $image_url; ?>" 
                 alt="<?php echo $image_alt; ?>" 
                 itemprop="image"
                 loading="lazy">
        <?php endif; ?>
        
        <div class="content">
            <?php 
            $doubt_headline = get_field('doubt_headline', 'option');
            if ($doubt_headline) :
            ?>
                <h2 itemprop="headline"><?php echo esc_html($doubt_headline); ?></h2>
            <?php endif; ?>
            
            <?php 
            $link = get_field('doubt_link', 'option');
            if ($link && is_array($link)) :
                $link_url = isset($link['url']) ? esc_url($link['url']) : '';
                $link_title = isset($link['title']) ? esc_html($link['title']) : esc_html__('Saiba mais', 'agenciaaids');
                $link_target = isset($link['target']) ? esc_attr($link['target']) : '_self';
                
                if ($link_url) :
            ?>
                <a href="<?php echo $link_url; ?>" 
                   target="<?php echo $link_target; ?>"
                   <?php echo ($link_target === '_blank') ? 'rel="noopener noreferrer"' : ''; ?>
                   class="doubt-link">
                    <?php echo $link_title; ?>
                </a>
            <?php 
                endif;
            endif; 
            ?>
        </div>        
    </article>
</section>