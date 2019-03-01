<?php $theme = CardinalTheme::get_instance() ?>
<div id="list-search" class="list-search"
     data-more-label="<?php echo esc_attr(__('Plus de résultats', CardinalTheme::TEXTDOMAIN)) ?>"
     data-more=".list-search + .pagination">
    <?php while (have_posts()): the_post() ?>
        <div class="list-search__item">
            <?php get_template_part('partial/index-item', 'search') ?>
        </div>
    <?php endwhile ?>
</div>