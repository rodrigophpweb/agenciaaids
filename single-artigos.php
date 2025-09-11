<?php 
    get_header();
    get_template_part('partials/breadcrumb');
    echo '<div class="adsMobile paddingContent">';
        get_template_part('partials/sections/section-ads');
    echo '</div>';
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('paddingContent'); ?> itemscope itemtype="https://schema.org/Article">
        <header class="entry-header">
            <?php 
                ($category = get_the_category()[0] ?? null) ? 
                printf('<span class="post-category" itemprop="articleSection">%s</span>', esc_html($category->name)) : ''; 
            ?>

            <?php the_title('<h1 class="entry-title" itemprop="headline">','</h1>');?>
            <?php 
                $autor = get_field('autor');
                if ($autor): 
            ?>
                    <div class="post-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <span class="author-label">Por:</span>
                        <span class="author-name" itemprop="name"><?= esc_html($autor); ?></span>
                    </div>
            <?php endif; ?>
            
            <?php
                // Obtém a data e hora da publicação e última atualização
                $published = get_the_date('Y-m-d\TH:i:sP'); // Formato ISO 8601
                $published_human = get_the_date('d/m/Y \à\s H\hi'); // Formato legível

                $updated = get_the_modified_date('Y-m-d\TH:i:sP');
                $updated_human = get_the_modified_date('d/m/Y \à\s H\hi');

                // Calcula tempo estimado de leitura (assumindo média de 200 palavras por minuto)
                $word_count = str_word_count(strip_tags(get_post_field('post_content', get_the_ID())));
                $reading_time = ceil($word_count / 200);
            ?>

            <div class="post-meta">
                <span>
                    <time datetime="<?= esc_attr($published); ?>" itemprop="datePublished"><?= esc_html($published_human); ?></time>
                </span>
                
                <?php if ($published !== $updated): ?>
                    • Atualizado em <time datetime="<?= esc_attr($updated); ?>" itemprop="dateModified"><?= esc_html($updated_human); ?></time>
                <?php endif; ?>
                
                • <span itemprop="timeRequired" content="PT<?= esc_attr($reading_time); ?>M"><?= esc_html($reading_time); ?> min de leitura</span>
            </div>
        </header>
        
        <?php if (has_post_thumbnail()): ?>
            <figure class="post-thumbnail">
                <?php the_post_thumbnail('large', [
                    'itemprop' => 'image',
                    
                ]); ?>
            </figure>
        <?php endif; ?>

        <div class="entry-content" itemprop="articleBody">
            <?php 
                the_content();
                $post_url   = urlencode(get_permalink());
                $post_title = urlencode(get_the_title());
                $site_name  = urlencode(get_bloginfo('name'));
            ?>            

            <div class="share-buttons">
                <strong>Compartilhar:</strong>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $post_url ?>" target="_blank" rel="noopener" aria-label="Compartilhar no Facebook"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320C64 440 146.7 540.8 258.2 568.5L258.2 398.2L205.4 398.2L205.4 320L258.2 320L258.2 286.3C258.2 199.2 297.6 158.8 383.2 158.8C399.4 158.8 427.4 162 438.9 165.2L438.9 236C432.9 235.4 422.4 235 409.3 235C367.3 235 351.1 250.9 351.1 292.2L351.1 320L434.7 320L420.3 398.2L351 398.2L351 574.1C477.8 558.8 576 450.9 576 320z"/></svg></a>
                <a href="#" onclick="alert('O Instagram não permite compartilhamento direto por link.\nCopie o link ou compartilhe manualmente.')" aria-label="Compartilhar no Instagram"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M290.4 275.7C274 286 264.5 304.5 265.5 323.8C266.6 343.2 278.2 360.4 295.6 368.9C313.1 377.3 333.8 375.5 349.6 364.3C366 354 375.5 335.5 374.5 316.2C373.4 296.8 361.8 279.6 344.4 271.1C326.9 262.7 306.2 264.5 290.4 275.7zM432.7 207.3C427.5 202.1 421.2 198 414.3 195.3C396.2 188.2 356.7 188.5 331.2 188.8C327.1 188.8 323.3 188.9 320 188.9C316.7 188.9 312.8 188.9 308.6 188.8C283.1 188.5 243.8 188.1 225.7 195.3C218.8 198 212.6 202.1 207.3 207.3C202 212.5 198 218.8 195.3 225.7C188.2 243.8 188.6 283.4 188.8 308.9C188.8 313 188.9 316.8 188.9 320C188.9 323.2 188.9 327 188.8 331.1C188.6 356.6 188.2 396.2 195.3 414.3C198 421.2 202.1 427.4 207.3 432.7C212.5 438 218.8 442 225.7 444.7C243.8 451.8 283.3 451.5 308.8 451.2C312.9 451.2 316.7 451.1 320 451.1C323.3 451.1 327.2 451.1 331.4 451.2C356.9 451.5 396.2 451.9 414.3 444.7C421.2 442 427.4 437.9 432.7 432.7C438 427.5 442 421.2 444.7 414.3C451.9 396.3 451.5 356.9 451.2 331.3C451.2 327.1 451.1 323.2 451.1 319.9C451.1 316.6 451.1 312.8 451.2 308.5C451.5 283 451.9 243.6 444.7 225.5C442 218.6 437.9 212.4 432.7 207.1L432.7 207.3zM365.6 251.8C383.7 263.9 396.2 282.7 400.5 304C404.8 325.3 400.3 347.5 388.2 365.6C382.2 374.6 374.5 382.2 365.6 388.2C356.7 394.2 346.6 398.3 336 400.4C314.7 404.6 292.5 400.2 274.4 388.1C256.3 376 243.8 357.2 239.5 335.9C235.2 314.6 239.7 292.4 251.7 274.3C263.7 256.2 282.6 243.7 303.9 239.4C325.2 235.1 347.4 239.6 365.5 251.6L365.6 251.6zM394.8 250.5C391.7 248.4 389.2 245.4 387.7 241.9C386.2 238.4 385.9 234.6 386.6 230.8C387.3 227 389.2 223.7 391.8 221C394.4 218.3 397.9 216.5 401.6 215.8C405.3 215.1 409.2 215.4 412.7 216.9C416.2 218.4 419.2 220.8 421.3 223.9C423.4 227 424.5 230.7 424.5 234.5C424.5 237 424 239.5 423.1 241.8C422.2 244.1 420.7 246.2 419 248C417.3 249.8 415.1 251.2 412.8 252.2C410.5 253.2 408 253.7 405.5 253.7C401.7 253.7 398 252.6 394.9 250.5L394.8 250.5zM544 160C544 124.7 515.3 96 480 96L160 96C124.7 96 96 124.7 96 160L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 160zM453 453C434.3 471.7 411.6 477.6 386 478.9C359.6 480.4 280.4 480.4 254 478.9C228.4 477.6 205.7 471.7 187 453C168.3 434.3 162.4 411.6 161.2 386C159.7 359.6 159.7 280.4 161.2 254C162.5 228.4 168.3 205.7 187 187C205.7 168.3 228.5 162.4 254 161.2C280.4 159.7 359.6 159.7 386 161.2C411.6 162.5 434.3 168.3 453 187C471.7 205.7 477.6 228.4 478.8 254C480.3 280.3 480.3 359.4 478.8 385.9C477.5 411.5 471.7 434.2 453 452.9L453 453z"/></svg></a>
                <a href="https://api.whatsapp.com/send?text=<?= $post_title ?>%20<?= $post_url ?>" target="_blank" rel="noopener" aria-label="Compartilhar no WhatsApp"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M188.1 318.6C188.1 343.5 195.1 367.8 208.3 388.7L211.4 393.7L198.1 442.3L248 429.2L252.8 432.1C273 444.1 296.2 450.5 319.9 450.5L320 450.5C392.6 450.5 453.3 391.4 453.3 318.7C453.3 283.5 438.1 250.4 413.2 225.5C388.2 200.5 355.2 186.8 320 186.8C247.3 186.8 188.2 245.9 188.1 318.6zM370.8 394C358.2 395.9 348.4 394.9 323.3 384.1C286.5 368.2 261.5 332.6 256.4 325.4C256 324.8 255.7 324.5 255.6 324.3C253.6 321.7 239.4 302.8 239.4 283.3C239.4 264.9 248.4 255.4 252.6 251C252.9 250.7 253.1 250.5 253.3 250.2C256.9 246.2 261.2 245.2 263.9 245.2C266.5 245.2 269.2 245.2 271.5 245.3L272.3 245.3C274.6 245.3 277.5 245.3 280.4 252.1C281.6 255 283.4 259.4 285.3 263.9C288.6 271.9 292 280.2 292.6 281.5C293.6 283.5 294.3 285.8 292.9 288.4C289.5 295.2 286 298.8 283.6 301.4C280.5 304.6 279.1 306.1 281.3 310C296.6 336.3 311.9 345.4 335.2 357.1C339.2 359.1 341.5 358.8 343.8 356.1C346.1 353.5 353.7 344.5 356.3 340.6C358.9 336.6 361.6 337.3 365.2 338.6C368.8 339.9 388.3 349.5 392.3 351.5C393.1 351.9 393.8 352.2 394.4 352.5C397.2 353.9 399.1 354.8 399.9 356.1C400.8 358 400.8 366 397.5 375.2C394.2 384.5 378.4 392.9 370.8 394zM544 160C544 124.7 515.3 96 480 96L160 96C124.7 96 96 124.7 96 160L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 160zM244.1 457.9L160 480L182.5 397.8C168.6 373.8 161.3 346.5 161.3 318.5C161.4 231.1 232.5 160 319.9 160C362.3 160 402.1 176.5 432.1 206.5C462 236.5 480 276.3 480 318.7C480 406.1 407.3 477.2 319.9 477.2C293.3 477.2 267.2 470.5 244.1 457.9z"/></svg></a>
                <a href="https://twitter.com/intent/tweet?url=<?= $post_url ?>&text=<?= $post_title ?>" target="_blank" rel="noopener" aria-label="Compartilhar no X (Twitter)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M160 96C124.7 96 96 124.7 96 160L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 160C544 124.7 515.3 96 480 96L160 96zM457.1 180L353.3 298.6L475.4 460L379.8 460L305 362.1L219.3 460L171.8 460L282.8 333.1L165.7 180L263.7 180L331.4 269.5L409.6 180L457.1 180zM419.3 431.6L249.4 206.9L221.1 206.9L392.9 431.6L419.3 431.6z"/></svg></a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $post_url ?>" target="_blank" rel="noopener" aria-label="Compartilhar no LinkedIn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M160 96C124.7 96 96 124.7 96 160L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 160C544 124.7 515.3 96 480 96L160 96zM165 266.2L231.5 266.2L231.5 480L165 480L165 266.2zM236.7 198.5C236.7 219.8 219.5 237 198.2 237C176.9 237 159.7 219.8 159.7 198.5C159.7 177.2 176.9 160 198.2 160C219.5 160 236.7 177.2 236.7 198.5zM413.9 480L413.9 376C413.9 351.2 413.4 319.3 379.4 319.3C344.8 319.3 339.5 346.3 339.5 374.2L339.5 480L273.1 480L273.1 266.2L336.8 266.2L336.8 295.4L337.7 295.4C346.6 278.6 368.3 260.9 400.6 260.9C467.8 260.9 480.3 305.2 480.3 362.8L480.3 480L413.9 480z"/></svg></a>
                <!-- <button type="button" onclick="copyToClipboard('<?=get_permalink();?>')" aria-label="Copiar link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M288 64C252.7 64 224 92.7 224 128L224 384C224 419.3 252.7 448 288 448L480 448C515.3 448 544 419.3 544 384L544 183.4C544 166 536.9 149.3 524.3 137.2L466.6 81.8C454.7 70.4 438.8 64 422.3 64L288 64zM160 192C124.7 192 96 220.7 96 256L96 512C96 547.3 124.7 576 160 576L352 576C387.3 576 416 547.3 416 512L416 496L352 496L352 512L160 512L160 256L176 256L176 192L160 192z"/></svg></button> -->
            </div>      
    </article>
<?php 
    get_template_part('', 'related-news');
    get_template_part('partials/partners');
    get_footer();
