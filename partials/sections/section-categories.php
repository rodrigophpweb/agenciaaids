<section class="paddingContent">
    <header>
        <!-- Name category -->
        <h1><?php single_cat_title(); ?></h1>
        <span><?php echo category_description(); ?></span>

        <div class="filters">
            <select name="year_filter" id="year_filter">
                <option value="">Todos os anos</option>
                <?php
                    $years = range(date('Y'), 2006);
                    foreach ($years as $year) {
                        echo '<option value="' . esc_attr($year) . '">' . esc_html($year) . '</option>';
                    }
                ?>
            </select>

            <select name="month_filter" id="month_filter">
                <option value="">Todos os meses</option>
                <?php
                    $months_name = [
                        1 => 'Janeiro',
                        2 => 'Fevereiro',
                        3 => 'Março',
                        4 => 'Abril',
                        5 => 'Maio',
                        6 => 'Junho',
                        7 => 'Julho',
                        8 => 'Agosto',
                        9 => 'Setembro',
                        10 => 'Outubro',
                        11 => 'Novembro',
                        12 => 'Dezembro'
                    ];
                    foreach ($months_name as $month => $name) {
                        echo '<option value="' . esc_attr($month) . '">' . esc_html($name) . '</option>';
                    }
                ?>
            </select>

            <select name="category_filter" id="category_filter">
                <option value="">Todas as categorias</option>
                <?php
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                    }
                ?>
            </select>
        </div>
    </header>
</section>

<section class="cards paddingContent">
    <!-- Loop to posts in category.php -->
    <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
    ?>
        <article>
            <figure>
                <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" alt="<?php the_title(); ?>">
            </figure>
            <div class="content">
                <h2><?php the_title('<h2>', '</h2>'); ?></h2>
                <?php
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        echo '<mark class="category">' . esc_html($categories[0]->name) . '</mark>';
                    }
                ?>
                <p><?php echo wp_trim_words(get_the_content(), 30, '...'); ?></p>
                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
            </div>
        </article>
    <?php
            endwhile;
        endif;
    ?>
</section>

