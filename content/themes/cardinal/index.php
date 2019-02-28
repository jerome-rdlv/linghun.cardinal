<?php $theme = CardinalTheme::get_instance() ?>
<?php $template_name = $theme->get_template_name() ?>
<?php get_header($template_name) ?>

<?php get_template_part('partial/splash', $template_name) ?>

    <div class="index container">

        <?php
        $title = '';
        if (is_search()) {
            $title = sprintf(
                __('Résultats de la recherche pour « %s »', CardinalTheme::TEXTDOMAIN),
                get_search_query()
            );
        } elseif ($theme->archive_page()) {
            $title = get_the_title();
            wp_reset_query();
        } else {
            $title = get_the_archive_title();
        }
        ?>

        <h1 class="index__title visually-hidden">
            <?php echo $title ?>
        </h1>

        <?php if (!is_search()): ?>
            <?php get_template_part('partial/filter', get_post_type()) ?>
        <?php endif ?>

        <?php if (have_posts()): ?>
            <?php if (!is_search()): ?>
                <p class="visually-hidden"><?php echo $title ?> :</p>
            <?php endif ?>
            <?php get_template_part('partial/index-list', $template_name) ?>
            <?php get_template_part('partial/pagination') ?>
        <?php else: ?>
            <p class="index__empty">
                Aucun résultat.
            </p>
        <?php endif ?>
    </div>

<?php get_footer($template_name) ?>