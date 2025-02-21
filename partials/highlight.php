<section class="highlight <?=$args['class']?>">
    <article>
        <span class="category">Destaque</span>
        <h1>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h1>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Aliquid reprehenderit est explicabo similique odio, error expedita quis voluptatem rem, qui ipsum atque animi ipsam dolores exercitationem, repellat facere consectetur commodi.</p>
        <time datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date('d \d\e F \d\e Y'); ?></time>

    </article>
    <figure>
        <?php the_post_thumbnail()?>
    </figure>
</section>