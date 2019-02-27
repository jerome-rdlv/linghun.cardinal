<?php $theme = CardinalTheme::get_instance() ?>
<div class="splash has-background">
    <?php
    $thumbnail_id = get_field('splash_img', 'options', false);
    if ($theme->archive_page()) {
        $thumbnail_id = get_post_thumbnail_id();
        wp_reset_query();
    }
    ?>
    <?php echo wp_get_attachment_image($thumbnail_id, 'large', false, [
        'class'           => 'splash__image has-background__image',
        'data-object-fit' => 'cover',
        'sizes'           => '100vw',
    ]) ?>
</div>
