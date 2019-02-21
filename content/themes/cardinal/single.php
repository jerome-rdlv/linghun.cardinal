<?php $theme = CardinalTheme::get_instance() ?>
<?php $theme->add_symbol('arrow-prev') ?>
<?php $theme->add_symbol('arrow-next') ?>
<?php get_header() ?>
<?php the_post() ?>

    <div class="single container">
        <h1 class="single__title">
            <?php the_title() ?>
        </h1>

        <time class="visually-hidden" datetime="<?php echo get_the_date('Y-m-d') ?>">
            <?php printf(__('Publié le %s', CardinalTheme::TEXTDOMAIN), get_the_date('d M Y')) ?>
        </time>

        <p class="single__chapo">
            <?php the_field('chapo') ?>
        </p>

        <div class="single__content">
            <?php get_template_part('partial/blocks') ?>
        </div>

        <?php get_template_part('partial/pager') ?>
    </div>

<?php get_footer() ?>