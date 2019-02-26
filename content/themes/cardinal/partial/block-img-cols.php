<?php $images = get_sub_field('images') ?>
<?php if ($images): ?>
    <div class="block-img-cols">
        <p class="block-img-cols__left">
            <?php if (!empty($images[0])) {
                echo wp_get_attachment_image($images[0]['ID'], 'medium', false, [
                    'class' => 'block-img-cols__image',
                    'data-object-fit' => 'cover',
                    'sizes' => '(min-width: 1170px) 55.5rem, (min-width: 460px) 50vw, 100vw',
                ]);
            } ?>
        </p>
        <p class="block-img-cols__right">
            <?php if (!empty($images[1])) {
                echo wp_get_attachment_image($images[1]['ID'], 'medium', false, [
                    'class' => 'block-img-cols__image',
                    'sizes' => '(min-width: 1170px) 55.5rem, (min-width: 460px) 50vw, 100vw',
                ]);
            } ?>
            <?php if (!empty($images[2])) {
                echo wp_get_attachment_image($images[2]['ID'], 'medium', false, [
                    'class' => 'block-img-cols__image',
                    'sizes' => '(min-width: 1170px) 55.5rem, (min-width: 460px) 50vw, 100vw',
                ]);
            } ?>
        </p>
    </div>
<?php endif ?>
