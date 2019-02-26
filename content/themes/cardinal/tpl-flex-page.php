<?php /* Template name: Page flex */ ?>
<?php $theme = CardinalTheme::get_instance() ?>
<?php get_header() ?>
<?php the_post() ?>

    <div class="splash has-background">
        <?php
        $thumbnail_id = get_post_thumbnail_id();
        if (!$thumbnail_id) {
            $thumbnail_id = get_field('splash_img', 'options', false);
        }
        ?>
        <?php echo wp_get_attachment_image($thumbnail_id, 'large', false, [
            'class'           => 'splash__image has-background__image',
            'data-object-fit' => 'cover',
            'sizes'           => '100vw',
        ]) ?>
    </div>

    <div class="flex-page container">
        <h1 class="flex-page__title visually-hidden">
            <?php the_title() ?>
        </h1>

        <p class="flex-page__accroche">
            <?php the_field('accroche') ?>
        </p>

        <div class="flex-page__content">
            <?php get_template_part('partial/blocks') ?>
        </div>
    </div>

<?php get_footer() ?>