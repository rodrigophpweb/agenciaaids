<?php
    get_header();
    get_template_part('partials/breadcrumb');
    echo '<div class="adsMobile paddingContent">';
        get_template_part('partials/sections/section-ads');
    echo '</div>';
    get_template_part('partials/sections/section-categories');
    get_template_part('partials/partners');
    get_footer();
?>