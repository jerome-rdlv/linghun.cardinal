<div class="card-real fade-in fade-in--hidden">
    <a class="card-real__link" href="<?php the_permalink() ?>">
        <span class="card-real__title">
            <?php echo wp_trim_words(get_the_title(), 6, '…') ?>
        </span>
        <span class="visually-hidden">-</span>
        <span class="card-real__place">
            <span class="visually-hidden">
                <?php _e('Lieu :', CardinalTheme::TEXTDOMAIN) ?>
            </span>
            <?php the_field('place') ?>
        </span>
    </a>
    <?php
    $thumbnail_id = get_field('thumbnail_alt', false, false);
    if (!$thumbnail_id) {
        $thumbnail_id = get_post_thumbnail_id();
    }
    ?>
    <?php echo wp_get_attachment_image($thumbnail_id, 'medium', false, [
        'class'           => 'card-real__thumb',
//        'data-object-fit' => 'cover',
        'sizes'           => '(min-width: 1040px) 26.5rem, (min-width: 780px) 33vw, (min-width: 520px) 50vw, 100vw',
    ]) ?>
</div>