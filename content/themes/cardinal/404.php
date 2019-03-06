<?php $theme = CardinalTheme::get_instance() ?>
<?php get_header() ?>

<?php get_template_part('partial/splash') ?>

    <div class="singular container">
        <h1 class="singular__title">
            <?php _e('Introuvable', CardinalTheme::TEXTDOMAIN) ?>
        </h1>
        <p class="singular__content content empty">
            <?php _e('La page que vous demandez n’existe pas. Vous avez pu être mené ici par un lien cassé, à moins que vous n’ayez fait une faute de frappe dans l’adresse que vous vouliez atteindre.', CardinalTheme::TEXTDOMAIN) ?>
        </p>
    </div>

<?php get_footer() ?>