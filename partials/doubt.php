<section class="doubt paddingContent" itemscope itemtype="https://schema.org/FAQPage">
    <article class="info">
        <?php $doubt_image = get_field('doubt_image','option')?>
        <img src="<?=$doubt_image['url']?>" alt="<?=$doubt_image['alt']?>" itemprop="image">    
        
        <div class="content">
            <h2 itemprop="headline"><?php the_field('doubt_headline','option')?></h2>
            <?php $link = get_field('doubt_link', 'option')?>
            <!-- <a href="<?= $link['url']?>" itemprop="url" class="btnMorePost"><?=$link['title']?></a> -->
        </div>        
    </article>
</section>