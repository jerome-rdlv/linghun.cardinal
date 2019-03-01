<?php $theme = CardinalTheme::get_instance() ?>
<div id="list-search" class="list-search"
     data-more-pagination=".list-search + .pagination"
     data-more-options="<?php echo esc_attr(json_encode([
         'label'           => __('Plus de résultats', CardinalTheme::TEXTDOMAIN),
     ])) ?>">
    <?php while (have_posts()): the_post() ?>
        <div class="list-search__item">
            <?php get_template_part('partial/index-item', 'search') ?>
        </div>
    <?php endwhile ?>
</div>