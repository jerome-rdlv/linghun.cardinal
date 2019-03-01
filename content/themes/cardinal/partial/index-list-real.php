<?php $theme = CardinalTheme::get_instance() ?>
<?php global $wp_query ?>
<ul id="list-real" class="list-real" data-masonry='{ "itemSelector": ".list-real__item" }'
    data-more=".list-real + .pagination"
    data-more-loading-text="<?php echo esc_attr(__('Chargement des réalisations suivantes.', CardinalTheme::TEXTDOMAIN)) ?>"
    data-more-loading-success="<?php echo esc_attr(sprintf(__('%s réalisations chargées.', CardinalTheme::TEXTDOMAIN), $wp_query->post_count)) ?>"
>
    <?php while (have_posts()): the_post() ?>
        <li class="list-real__item">
            <?php get_template_part('partial/index-item', 'real') ?>
        </li>
    <?php endwhile ?>
</ul>