<section class="doubt" itemscope itemtype="https://schema.org/FAQPage">
    <div class="info">
        <h2 itemprop="headline"><?php the_field('doubt_headline','option')?></h2>
        <?php $link = get_field('doubt_link', 'option')?>
        <a href="<?= $link['url']?>" itemprop="url"><?=$link['title']?></a>
    </div>
    <figure>
        <?php $doubt_image = get_field('doubt_image','option')?>
        <img src="<?=$doubt_image['url']?>" alt="<?=$doubt_image['alt']?>" itemprop="image">
    </figure>
</section>