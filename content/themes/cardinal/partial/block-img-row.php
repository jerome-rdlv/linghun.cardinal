<?php $images = get_sub_field('images') ?>
<?php if ($images): ?>
    <?php
    $maxWidth = 1140;
    $rowWidth = 0;
    foreach ($images as &$image) {
        // adjust height to same reference, then get resulting width
        $image['nwidth'] = $image['width'] / ($image['height'] / 100);
        $rowWidth += $image['nwidth'];
    }
    ?>
    <div class="block-img-row">
        <?php foreach ($images as &$image): ?>
            <?php $basis = round($image['nwidth'] * 100 / $rowWidth, 4) ?>
            <div class="block-img-row__item" style="flex-basis: <?php echo $basis ?>%;">
                <?php echo wp_get_attachment_image($image['ID'], 'medium', false, [
                    'class'           => 'block-img-row__image',
                    'data-object-fit' => 'cover',
                    'sizes'           => '(min-width: 1170px) '. round($basis / 100 * $maxWidth) .'px, (min-width: 600px) '. round($basis) .'vw, 100vw',
                ]) ?>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>