<?php

    // Letra atual (ex.: ?letra=A)
    $letters = range('A', 'Z');
    $current_letter = isset($_GET['letra']) ? strtoupper(sanitize_text_field($_GET['letra'])) : 'A';
    if (!in_array($current_letter, $letters, true)) {
    $current_letter = ''; // todas as letras
    }

    // Query: por título, asc; filtra primeira letra com posts_where
    add_filter('posts_where', function ($where, \WP_Query $query) use ($current_letter) {
    global $wpdb;
    if ($query->get('starts_with_letter')) {
        $letter = $query->get('starts_with_letter');
        // LIKE 'A%' — costuma ser acento-insensível (collation utf8mb4_unicode_ci)
        $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", $letter . '%');
    }
    return $where;
    }, 10, 2);

    $args = [
    'post_type'      => 'dicionarios',
    'post_status'    => 'publish',
    'posts_per_page' => -1,           
    'orderby'        => 'title',
    'order'          => 'ASC',
    ];


    if ($current_letter !== '') {
    $args['starts_with_letter'] = $current_letter;
    }

    $query = new WP_Query($args);
?>

<section class="content-dictionary paddingContent" itemscope itemtype="https://schema.org/DefinedTermSet">

  <?php
  echo '<h1 itemprop="name">' . esc_html(get_the_archive_title()) . '</h1>';
  if (get_the_archive_description()) {
    echo '<div class="archive-description" itemprop="description">';
    echo wp_kses_post(get_the_archive_description());
    echo '</div>';
  }
  ?>

  <nav class="alphabet-navigation" role="navigation" aria-label="Navegação por letras do dicionário" data-current-letter="<?php echo esc_attr($current_letter); ?>">
    <button aria-label="Letras anteriores" class="alphabet-prev" type="button">&lt;</button>

    <ul class="alphabet-list" role="list">
      <li>
        <a class="<?php echo $current_letter === '' ? 'is-active' : ''; ?>" href="<?php echo esc_url(remove_query_arg('letra')); ?>" aria-label="Todas as letras" aria-current="<?php echo $current_letter === '' ? 'page' : 'false'; ?>">Todas
        </a>
      </li>
      <?php foreach ($letters as $letter): ?>
        <li>
          <a href="<?php echo esc_url(add_query_arg('letra', $letter)); ?>" class="<?php echo $current_letter === $letter ? 'is-active' : ''; ?>" aria-label="Letra <?php echo esc_attr($letter); ?>" aria-current="<?php echo $current_letter === $letter ? 'page' : 'false'; ?>">
            <?php echo esc_html($letter); ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <button aria-label="Próximas letras" class="alphabet-next" type="button">&gt;</button>
  </nav>

  <?php $set_itemid = esc_url(get_post_type_archive_link('dicionario')); ?>

  <section class="faqDictionary" aria-live="polite" aria-busy="false">
    <?php if ($query->have_posts()): ?>
      <?php while ($query->have_posts()): $query->the_post(); ?>
        <?php
          $title = get_the_title();
          $term_anchor = sanitize_title($title);
          $first_letter = strtoupper(substr(remove_accents($title), 0, 1));
        ?>

        <details class="dictionary-item" id="<?php echo esc_attr($term_anchor); ?>" data-letter="<?php echo esc_attr($first_letter); ?>" itemscope itemprop="hasDefinedTerm" itemtype="https://schema.org/DefinedTerm">

          <summary class="question" role="button" aria-expanded="false" itemprop="name">
            <?php echo esc_html($title); ?>
          </summary>

          <article class="answer" role="region" aria-label="<?php echo esc_attr($title); ?>" itemprop="description">
            <?php the_content(); ?>
          </article>

          <link itemprop="inDefinedTermSet" href="<?php echo $set_itemid; ?>" />
          <meta itemprop="url" content="<?php echo esc_url(get_permalink()); ?>" />
        </details>

      <?php endwhile; wp_reset_postdata(); ?>
    <?php else: ?>
      <p role="status">Nenhum termo encontrado<?php echo $current_letter ? ' para a letra ' . esc_html($current_letter) : ''; ?>.</p>
    <?php endif; ?>
  </section>
</section>