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
         data-more-url="<?php next_posts($wp_query->max_num_pages) ?>"
         data-more-label="<?php echo esc_attr(__('Afficher plus', CardinalTheme::TEXTDOMAIN)) ?>"
         data-more-loading-label="<?php echo esc_attr(__('Chargement…', CardinalTheme::TEXTDOMAIN)) ?>"
         data-more-loading-text="<?php echo esc_attr(__('Chargement des articles suivants.', CardinalTheme::TEXTDOMAIN)) ?>"
         data-more-loading-success="<?php echo esc_attr(sprintf(__('%s articles chargés.', CardinalTheme::TEXTDOMAIN), $wp_query->post_count)) ?>"
    >
        <h2 class="visually-hidden print-off">
            <?php _e('Pagination', CardinalTheme::TEXTDOMAIN) ?>
        </h2>
        <p class="pagination__numbers visually-hidden">
            <?php printf(
                __('Articles <strong>%s à %s</strong> sur %s', CardinalTheme::TEXTDOMAIN),
                '<span class="pagination__start">'. $start_offset .'</span>',
                '<span class="pagination__end">'. $end_offset .'</span>',
                '<span class="pagination__total">'. $wp_query->found_posts .'</span>'
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