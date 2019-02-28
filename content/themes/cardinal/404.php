<?php $theme = CardinalTheme::get_instance() ?>
<?php get_header() ?>
<?php the_post() ?>

<?php get_template_part('partial/splash') ?>

    <div class="singular container">
        <h1 class="singular__title">
            Introuvable
        </h1>
        <div class="singular__content content empty">
            <p>La page que vous recherchez n’existe pas.</p>
        </div>
    </div>

<?php get_footer() ?>