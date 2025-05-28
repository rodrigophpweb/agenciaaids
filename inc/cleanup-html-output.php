<?php
/**
 * Remove o script Speculation Rules do HTML final
 * @package AgenciaAids
 */

add_action('template_redirect', function () {
    ob_start(function ($html) {
        return preg_replace(
            '#<script type="speculationrules">.*?</script>#is',
            '',
            $html
        );
    });
});
