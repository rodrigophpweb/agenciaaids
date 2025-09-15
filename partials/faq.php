<article itemscope itemtype="https://schema.org/FAQPage">
    <header class="faq">
        <?php the_title('<span class="subtitle"></span>', '</span>'); ?>
        <h1><?php the_field('faq_title'); ?></h1>

        <nav class="faq_nav">
            <?php
                $faq = get_field('faq');
                if ($faq) :
                    foreach ($faq as $f) :?>
                        <button class="category-link" data-category-id="<?=$f['faq_id']?>"><?=$f['faq_question']?></button>
                    <?php endforeach;
                endif;
            ?>
        </nav>
    </header>

    <section itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" class="question">
        <?php
            $category_id = get_queried_object_id();
            $args = array(
                'category' => $category_id,
                'posts_per_page' => -1
            );
            $posts = get_posts($args);
            if ($posts) {
                foreach ($posts as $post) {
                    setup_postdata($post);
                    ?>
                    
                        <details itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                            <?php the_title('<summary itemprop="name">', '</summary>')?>
                            <article itemprop="text">
                                <?php the_content(); ?>
                            </article>
                        </details>
                    <?php
                }
                wp_reset_postdata();
            }
        ?>
    </section>
</article>