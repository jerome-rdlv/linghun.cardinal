<?php $post_type_object = get_post_type_object(get_post_type()) ?>
<?php if ($post_type_object->taxonomies): ?>
    <?php $taxonomy = get_taxonomy($post_type_object->taxonomies[0]) ?>
    <?php $terms = get_terms([
        'taxonomy' => $taxonomy->name,
    ]) ?>
    <?php if ($terms && !$terms instanceof WP_Error): ?>
        <?php $current_term = get_term_by('slug', get_query_var($taxonomy->name), $taxonomy->name) ?>
        <div class="filter print-off">
            <p class="filter__title visually-hidden">
                <?php printf(
                    __('Filtrer par %s :', CardinalTheme::TEXTDOMAIN),
                    mb_strtolower($taxonomy->labels->singular_name)
                ) ?>
            </p>
            <ul class="filter__list link-list">
                <li class="filter__item link-list__item<?php echo !$current_term ? '  link-list__item--current' : '' ?>">
                    <?php if ($current_term): ?>
                        <a href="<?php echo get_post_type_archive_link($post_type_object->name) ?>"
                           class="filter__label">
                            <?php _e('Tout', CardinalTheme::TEXTDOMAIN) ?>
                        </a>
                    <?php else: ?>
                        <span class="filter__label">
                            <?php _e('Tout', CardinalTheme::TEXTDOMAIN) ?>
                        </span>
                    <?php endif ?>
                </li>
                <?php foreach ($terms as $term): ?>
                    <?php $current = $current_term && $current_term->term_id === $term->term_id ?>
                    <li class="filter__item link-list__item<?php echo $current ? ' link-list__item--current' : '' ?>">
                        <?php if ($current): ?>
                            <span class="filter__label">
                                <?php echo $term->name ?>
                            </span>
                        <?php else: ?>
                            <a href="<?php echo get_term_link($term) ?>" class="filter__label">
                                <?php echo $term->name ?>
                            </a>
                        <?php endif ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>
<?php endif ?>
