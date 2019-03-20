<?php $theme = CardinalTheme::get_instance() ?>
<?php get_header() ?>
<?php the_post() ?>

<?php get_template_part('partial/splash') ?>

    <div class="singular container">
        <h1 class="singular__title">
            <?php the_title() ?>
        </h1>

        <div class="singular__content content">
            <?php the_content() ?>
        </div>

        <div class="singular__flex">
            <?php get_template_part('partial/flex') ?>
        </div>
    </div>

<?php get_footer() ?>