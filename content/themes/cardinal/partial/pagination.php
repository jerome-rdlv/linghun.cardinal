<?php $pagination = paginate_links([
    'type'      => 'array',
    'prev_next' => false,
    'end_size'  => 2,
    'mid_size'  => 3,
]) ?>
<?php if ($pagination): ?>
    <div class="pagination">
        <h2 class="visually-hidden print-off"><?php _e('Pagination', CardinalTheme::TEXTDOMAIN) ?></h2>
        <p class="visually-hidden">
            <?php global $wp_query;
            <?php printf(
                __('Page <strong>%s</strong> / %s', CardinalTheme::TEXTDOMAIN),
                $paged ? $paged : '1',
                $wp_query->max_num_pages
            ) ?>
        </p>
        <ul class="pagination__list print-off">
            <?php foreach ($pagination as $item): ?>
                <li class="pagination__item"><?php echo $item ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>