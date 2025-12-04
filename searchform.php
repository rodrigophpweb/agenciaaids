<!-- Preciso criar um searchform com uma lupa -->
<form role="search" method="get" class="searchform" action="<?php echo esc_url(home_url('/')); ?>" itemscope itemtype="https://schema.org/SearchAction">
    <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder') ?>" value="<?php echo esc_attr(get_search_query()) ?>" name="s" title="<?php echo esc_attr_x('Search for:', 'label') ?>" itemprop="query-input">
    <button type="submit" class="search-submit" title="<?php echo esc_attr_x('Search', 'submit button') ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </button>
</form>
