<article itemscope itemtype="https://schema.org/FAQPage">
    <section class="faq">
        <?php the_title('<span class="subtitle"></span>', '</span>'); ?>
        <h1><?php the_field('faq_title'); ?></h1>

        <nav class="faq_nav">
            <ul>
                <?php
                $faq = get_field('faq');
                if ($faq) {
                    foreach ($faq as $f) {
                        echo '<li><a href="#" class="category-link" data-category-id="' . $f['faq_id'] . '">' . $f['faq_question'] . '</a></li>';
                    }
                }
                ?>
            </ul>
        </nav>

    </section>

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
            <section itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" class="question">
                <summary itemprop="name"><?php the_title(); ?></summary>
                <details itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <div itemprop="text"><?php the_content(); ?></div>
                </details>
            </section>
            <?php
        }
        wp_reset_postdata();
    }
    ?>
</article>