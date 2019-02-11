<?php $theme = CardinalTheme::get_instance() ?>
<?php get_header() ?>

    <div class="index container">
        
        <h1 class="index__title">
            <?php if (is_search()): ?>
                <?php printf(
                    __('Résultats de la recherche pour « %s »', CardinalTheme::TEXTDOMAIN),
                    get_search_query()
                ) ?>
            <?php elseif (is_archive()): ?>
                <?php _e('Archive', CardinalTheme::TEXTDOMAIN) ?>
            <?php endif ?>
        </h1>

        <?php if (have_posts()): ?>
            <div class="index__list">
                <?php while (have_posts()): the_post() ?>
                    <div class="index__item">
                        <h2 class="index__label">
                            <a class="index__link" href="<?php the_permalink() ?>">
                                <?php the_title() ?>
                            </a>
                        </h2>
                        <div class="index__excerpt">
                            <span class="visually-hidden"><?php _e('Extrait :', CardinalTheme::TEXTDOMAIN) ?></span>
                            <?php echo apply_filters('the_excerpt', $theme->get_the_excerpt($post)) ?>
                        </div>
                    </div>
                <?php endwhile ?>
            </div>
            <?php /** @noinspection PhpIncludeInspection */
            include get_template_directory() . '/partial/pagination.php' ?>
        <?php else: ?>
            <p class="actus__empty">
                Aucun résultat.
            </p>
        <?php endif ?>
    </div>

<?php get_footer() ?>