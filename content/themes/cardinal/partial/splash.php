<?php $theme = CardinalTheme::get_instance() ?>
<div class="splash has-background">
    <?php
    $thumbnail_id = null;
    if (is_singular()) {
        $thumbnail_id = get_post_thumbnail_id();
    } elseif ($theme->archive_page()) {
        $thumbnail_id = get_post_thumbnail_id();
        wp_reset_query();
    }
    if (!$thumbnail_id) {
        $thumbnail_id = get_field('splash_img', 'options', false);
    }
    ?>
    <?php if ($thumbnail_id): ?>
        <?php echo wp_get_attachment_image($thumbnail_id, 'large', false, [
            'class'           => 'splash__image has-background__image',
            'data-object-fit' => 'cover',
            'sizes'           => '100vw',
        ]) ?>
    <?php endif ?>
</div>
