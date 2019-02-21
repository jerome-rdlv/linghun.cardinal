<?php $images = get_sub_field('images') ?>
<?php if ($images): ?>
    <div class="block-img-row">
        <?php foreach ($images as $image): ?>
            <div class="block-img-row__item">
                <?php echo wp_get_attachment_image($image['ID'], 'medium', false, [
                    'class' => 'block-img-row__image',
                    'sizes' => '',
                ]) ?>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>