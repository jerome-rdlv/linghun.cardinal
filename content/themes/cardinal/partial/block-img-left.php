<div class="block-img-left">
    <div class="block-img-left__left">
        <?php echo wp_get_attachment_image(get_sub_field('image', false), 'medium', false, [
            'class' => 'block-img-left__image',
            'sizes' => '(min-width: 1170px) 55.5rem, (min-width: 600px) 50vw, 100vw',
        ]) ?>
    </div>
    <div class="block-img-left__right">
        <div class="block-img-left__content content">
            <?php echo apply_filters('the_content', get_sub_field('content')) ?>
        </div>
    </div>
</div>
