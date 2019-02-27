<?php $theme = CardinalTheme::get_instance() ?>
<?php get_header() ?>

<?php get_template_part('partial/splash') ?>

    <div class="index container">

        <?php
        $title = '';
        if ($theme->archive_page()) {
            $title = get_the_title();
            wp_reset_query();
        } else {
            $title = get_the_archive_title();
        }
        ?>
       
        <h1 class="index__title visually-hidden">
            <?php echo $title ?>
        </h1>

        <?php get_template_part('partial/filter', get_post_type()) ?>

        <?php if (have_posts()): ?>
            <?php $template_name = is_search() ? 'search' : get_post_type() ?>
            <p class="visually-hidden"><?php echo $title ?> :</p>
            <ul class="index__list index__list--<?php echo $template_name ?>">
                <?php while (have_posts()): the_post() ?>
                    <li class="index__item">
                        <?php get_template_part('partial/index-item', $template_name) ?>
                    </li>
                <?php endwhile ?>
            </ul>
            <?php get_template_part('partial/pagination') ?>
        <?php else: ?>
            <p class="actus__empty">
                Aucun résultat.
            </p>
        <?php endif ?>
    </div>

<?php get_footer() ?>