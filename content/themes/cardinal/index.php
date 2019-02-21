<?php $theme = CardinalTheme::get_instance() ?>
<?php get_header() ?>

    <div class="splash has-background">
        <?php
        $thumbnail_id = get_field('splash_img', 'options', false);
        if ($theme->archive_page()) {
            $thumbnail_id = get_post_thumbnail_id();
            wp_reset_query();
        }
        ?>
        <?php echo wp_get_attachment_image($thumbnail_id, 'large', false, [
            'class'           => 'splash__image has-background__image',
            'data-object-fit' => 'cover',
            'sizes'           => '100vw',
        ]) ?>
    </div>

    <div class="index container">

        <h1 class="index__title">
            <?php if (is_search()): ?>
                <?php printf(
                    __('Résultats de la recherche pour « %s »', CardinalTheme::TEXTDOMAIN),
                    get_search_query()
                ) ?>
            <?php elseif (is_archive() || is_home()): ?>
                <?php if ($theme->archive_page()): ?>
                    <?php the_title() ?>
                    <?php wp_reset_query() ?>
                <?php else: ?>
                    <?php get_the_archive_title() ?>
                <?php endif ?>
            <?php endif ?>
        </h1>

        <?php if (!is_search()): ?>
            <?php get_template_part('partial/filter', get_post_type()) ?>
        <?php endif ?>

        <?php if (have_posts()): ?>
            <div class="index__list">
                <?php $template_name = is_search() ? 'search' : get_post_type() ?>
                <?php while (have_posts()): the_post() ?>
                    <div class="index__item">
                        <?php get_template_part('partial/index-item', $template_name) ?>
                    </div>
                <?php endwhile ?>
            </div>
            <?php get_template_part('partial/pagination') ?>
        <?php else: ?>
            <p class="actus__empty">
                Aucun résultat.
            </p>
        <?php endif ?>
    </div>

<?php get_footer() ?>