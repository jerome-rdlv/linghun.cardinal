<?php $theme = CardinalTheme::get_instance() ?>
<?php get_header() ?>
<?php the_post() ?>

<div class="singular container">
    <h1 class="singular__title">
        <?php the_title() ?>
    </h1>

    <div class="singular__content">
        <?php the_content() ?>
    </div>
</div>

<?php get_footer() ?>