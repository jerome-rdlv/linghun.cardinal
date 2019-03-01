<?php $theme = CardinalTheme::get_instance() ?>
<?php $pagination = paginate_links([
    'type'      => 'array',
    'prev_next' => false,
    'end_size'  => 2,
    'mid_size'  => 3,
]) ?>
<?php if ($pagination): ?>
    <?php global $wp_query ?>
    <?php $paged = get_query_var('paged') ?>
    <?php $start_offset = ($paged ? $paged - 1 : 0) * $wp_query->get('posts_per_page') + 1 ?>
    <?php $end_offset = $start_offset + $wp_query->post_count - 1 ?>
    <div class="pagination"
         data-more-options="<?php echo esc_attr(json_encode([
             'url'             => next_posts($wp_query->max_num_pages, false),
             'label'           => __('Afficher plus', CardinalTheme::TEXTDOMAIN),
             'loadingLabel'   => __('Chargement…', CardinalTheme::TEXTDOMAIN),
             'loadingText'    => __('Chargement des articles suivants.', CardinalTheme::TEXTDOMAIN),
             'loadingSuccess' => sprintf(__('%s articles chargés.', CardinalTheme::TEXTDOMAIN), $wp_query->post_count),
         ])) ?>">
        <h2 class="pagination__title visually-hidden print-off">
            <?php _e('Pagination', CardinalTheme::TEXTDOMAIN) ?>
        </h2>
        <p class="pagination__numbers visually-hidden">
            <?php printf(
                __('Articles <strong>%s à %s</strong> sur %s', CardinalTheme::TEXTDOMAIN),
                '<span class="pagination__start">' . $start_offset . '</span>',
                '<span class="pagination__end">' . $end_offset . '</span>',
                '<span class="pagination__total">' . $wp_query->found_posts . '</span>'
            ) ?>
        </p>
        <ul class="pagination__list print-off">
            <?php foreach ($pagination as $item): ?>
                <li class="pagination__item"><?php echo $item ?></li>
            <?php endforeach ?>
        </ul>
    </div>
    <script><?php include $theme->dist_path('/js/init-pagination-more.js') ?></script>
<?php endif ?>