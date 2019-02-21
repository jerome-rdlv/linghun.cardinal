<?php $theme = CardinalTheme::get_instance() ?>
<?php $theme->add_symbol('arrow-prev') ?>
<?php $theme->add_symbol('arrow-next') ?>
<?php get_header() ?>
<?php the_post() ?>

    <div class="splash has-background">
        <?php the_post_thumbnail('large', [
            'class' => 'splash__image has-background__image',
            'sizes' => '100vw',
        ]) ?>
    </div>

    <div class="real-single container">
        <?php $images = get_field('images') ?>
        <div class="real-single__intro">
            <h1 class="real-single__title">
                <?php the_title() ?>
            </h1>
           
            <time class="visually-hidden" datetime="<?php echo get_the_date('Y-m-d') ?>">
                <?php printf(__('Publié le %s', CardinalTheme::TEXTDOMAIN), get_the_date('d M Y')) ?>
            </time>

            <div class="real-single__content">
                <div class="real-single__desc">
                    <?php the_field('desc') ?>
                </div>
                <div class="real-single__tech">
                    <?php the_field('tech') ?>
                </div>
            </div>
            <?php if (isset($images[0])) {
                echo wp_get_attachment_image($images[0]['ID'], 'medium', false, [
                    'class' => 'real-single__image-1',
                    'sizes' => '',
                ]);
            } ?>

        </div>

        <div class="real-single__content">
            <?php if ($images): ?>
                <?php for ($i = 1; $i < count($images); ++$i): ?>
                    <?php echo wp_get_attachment_image($images[$i]['ID'], 'medium', false, [
                        'class' => 'real-single__image-' . ($i + 1),
                        'sizes' => '',
                    ]) ?>
                <?php endfor ?>
            <?php endif ?>
        </div>

        <?php get_template_part('partial/pager') ?>
    </div>

<?php get_footer() ?>