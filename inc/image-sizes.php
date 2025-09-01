<?php
add_action('after_setup_theme', function () {
    add_image_size('latestVideo', 904, 450, true);
    add_image_size('thumbnail_aside_videos_home', 200, 200, true);
});