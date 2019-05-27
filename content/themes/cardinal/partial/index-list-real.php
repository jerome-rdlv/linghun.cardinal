<?php $theme = CardinalTheme::get_instance() ?>
<?php global $wp_query ?>
<ul id="list-real" class="list-real"
    <?php /*data-masonry="<?php echo esc_attr(json_encode([
        'itemSelector' => '.list-real__item',
//        'stagger' => 30,
    ])) ?>" */ ?>
    data-more-pagination=".list-real + .pagination"
    data-more-options="<?php echo esc_attr(json_encode([
        'loadingText'    => __('Chargement des réalisations suivantes.', CardinalTheme::TEXTDOMAIN),
        'loadingSuccess' => sprintf(
            __('%s réalisations chargées.', CardinalTheme::TEXTDOMAIN),
            $wp_query->post_count
        ),
    ])) ?>">
    <?php while (have_posts()): the_post() ?>
        <li class="list-real__item">
            <?php get_template_part('partial/index-item', 'real') ?>
        </li>
    <?php endwhile ?>
</ul>