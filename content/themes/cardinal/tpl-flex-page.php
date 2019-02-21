<?php /* Template name: Page flex */ ?>
<?php $theme = CardinalTheme::get_instance() ?>
<?php get_header() ?>
<?php the_post() ?>

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