<div class="block-img-left">
    <div class="block-img-left__left">
        <?php echo wp_get_attachment_image(get_sub_field('image', false), 'medium', false, [
            'class' => 'block-img-left__image',
            'sizes' => '',
        ]) ?>
    </div>
    <div class="block-img-left__rigth">
        <div class="block-img-left__content content">
            <?php the_sub_field('content') ?>
        </div>
    </div>
</div>
